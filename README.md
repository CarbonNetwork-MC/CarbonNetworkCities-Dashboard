# CarbonNetworkCities - Dashboard

The database includes an `onboarding_players` staging table used by the
onboarding server. It stores reconnect-safe progress and retains completed
rows. The `player_uuid` intentionally does not reference `players`, because an
onboarding row exists before the authoritative Cities player is created.

## Account, item, and starter-grant schema

The Cities data model includes:

- `personal_bank_accounts`, allowing multiple `checking` and `savings`
  accounts per player;
- an idempotent `bank_transactions` ledger with an explicit
  `opening_balance` type;
- `item_categories` and `items`, with immutable unique identifiers,
  configurable Bukkit materials, an enabled state, a definition version,
  type-specific JSON data, and optional player or dashboard creator provenance;
  and
- reusable `player_grants`, uniquely identified per player by their
  idempotency key and indexed for unclaimed-grant lookup.

`player_inventories` stores one versioned inventory envelope per player. Its
`revision` column is used for optimistic locking so a stale server cannot
overwrite a newer complete inventory snapshot.

The approved starter contract is configured in `config/cities.php`: one EUR
checking account with an opening balance of `5000.00`, one credit card bound to
the immutable personal-bank-account ID, and 32 `COOKED_BEEF` starter-food
items. Starter food explicitly has expiration disabled, so it will remain
outside the future food-decay system. C5 seeds the reusable catalog definitions
only; the transactional creation of player-specific accounts, ledger entries,
and grants is integrated with final onboarding in C8.

## Shared game clock

The singleton `game_clock` row is the authoritative definition used by every
Cities server. It stores paired real-time and game-time anchors, a decimal time
scale, an IANA time-zone identifier, and a monotonically increasing version.
Servers derive the current Cities time locally; the row is updated only when an
administrator changes the definition, not as time passes.

# CarbonNetworkCities - Dashboard

The database includes an `onboarding_players` staging table used by the
onboarding server. It stores reconnect-safe progress and retains completed
rows. The `player_uuid` intentionally does not reference `players`, because an
onboarding row exists before the authoritative Cities player is created.

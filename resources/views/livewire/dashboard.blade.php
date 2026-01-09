<div>
    Welcome back, {{ $user->name }}!

    <br>

    You are logged in as {{ $user->player->player->username }}

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="cursor-pointer text-blue-500 hover:text-blue-700">
            <span>Log out</span>
        </button>
    </form>
</div>

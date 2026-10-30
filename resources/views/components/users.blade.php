@props(['users'])

<ul x-cloak x-show="open">
    @foreach ($users as $user)
        <li>
            <span class="flex items-center gap-x-2 rounded-md px-4 py-1 hover:bg-fuchsia-900 hover:text-white">
                <span class="relative">
                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="size-6 rounded-md" />

                    <span class="absolute -right-1 -bottom-1">
                        <span class="bg-sidebar flex size-3.5 items-center justify-center rounded-full">
                            <span class="bg-sidebar flex size-2 rounded-full border border-gray-400"></span>
                        </span>
                    </span>
                </span>

                {{ $user->name }}
            </span>
        </li>
    @endforeach
</ul>

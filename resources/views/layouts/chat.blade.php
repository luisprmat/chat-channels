<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>
        {{ filled($title ?? null) ? $title.' - '.config('app.name', 'Laravel') : config('app.name', 'Laravel') }}
    </title>

    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset("images/apple-touch-icon.png") }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset("images/favicon-32x32.png") }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset("images/favicon-16x16.png") }}" />
    <link rel="icon" type="image/x-icon" href="{{ asset("images/favicon.ico") }}" />

    @fonts('sans')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-screen w-screen antialiased">
    <div class="flex h-full w-full flex-col items-center justify-center bg-linear-to-tr from-fuchsia-600 from-10% to-fuchsia-900 p-1">
        <div class="flex w-full items-center justify-between px-2 py-1.5">
            <!-- Window actions -->
            <div class="flex gap-x-2">
                <span class="size-3 rounded-full bg-red-400"></span>

                <span class="size-3 rounded-full bg-yellow-400"></span>

                <span class="size-3 rounded-full bg-green-400"></span>
            </div>

            <!-- Controls -->
            <div class="flex w-1/2 items-center gap-x-2">
                <x-icons.back class="size-5 text-white/20" />

                <x-icons.forward class="size-5 text-white/20" />

                <x-icons.clock class="size-5 text-white" />

                <input
                    type="search"
                    class="w-full rounded-sm bg-white/20 px-3 py-1 text-sm placeholder:text-white"
                    placeholder="Search Laravel"
                />
            </div>

            <!-- Help -->
            <a href="{{ route("dashboard") }}">
                <x-icons.question class="size-5 text-white" />
            </a>
        </div>

        <div class="flex w-full">
            <!-- Sidebar -->
            <div class="flex flex-col justify-between py-2 pr-5 pl-3.5">
                <ul class="flex flex-col gap-y-4">
                    <li>
                        <img src="{{ asset("images/laravel.png") }}" alt="Avatar" class="size-10 rounded-md" />
                    </li>

                    <li class="flex size-10 items-center justify-center rounded-md bg-white/20 text-white">
                        <x-icons.home class="size-6" />
                    </li>
                </ul>

                <form action="{{ route("logout") }}" method="post" class="mb-2">
                    @csrf
                    @method('delete')

                    <button type="submit" class="relative">
                        <img
                            src="{{ Auth::user()->avatar }}"
                            alt="{{ Auth::user()->name }}"
                            class="size-10 rounded-md"
                        />

                        <span class="absolute -right-1 -bottom-1">
                            <span class="flex size-3.5 items-center justify-center rounded-full bg-fuchsia-600">
                                <span class="flex size-2 rounded-full bg-green-600"></span>
                            </span>
                        </span>
                    </button>
                </form>
            </div>

            <!-- Workspace -->
            {{ $slot }}
        </div>
    </div>
</body>
</html>

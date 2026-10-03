<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login — Shiv Aaradhana Private Limited</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#091433] text-stone-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo & Header -->
        <div class="text-center mb-8 space-y-2">
            <div class="w-14 h-14 rounded-2xl bg-[#394F3D] border border-[#EBD6B4]/40 flex items-center justify-center font-heading font-black text-2xl text-[#EBD6B4] mx-auto shadow-xl">
                SA
            </div>
            <h1 class="text-2xl font-heading font-bold text-white tracking-wide">
                Shiv Aaradhana Private Limited
            </h1>
            <p class="text-xs text-stone-400">
                Authorized Staff Administration & Trade Portal
            </p>
        </div>

        <!-- Login Card -->
        <div class="bg-white text-stone-800 rounded-2xl shadow-2xl p-8 border border-stone-200 space-y-6">
            <div>
                <h2 class="text-lg font-bold text-[#091433]">Sign In to Management Console</h2>
                <p class="text-xs text-stone-500 mt-0.5">Please provide your authorized business credentials.</p>
            </div>

            @if($errors->any())
            <div class="p-3.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 text-xs">
                {{ $errors->first() }}
            </div>
            @endif

            <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">
                        Corporate Email Address
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full px-3.5 py-2.5 rounded-lg border border-stone-300 focus:ring-2 focus:ring-[#9C451B] focus:border-[#9C451B] text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">
                        Password
                    </label>
                    <input type="password" name="password" required class="w-full px-3.5 py-2.5 rounded-lg border border-stone-300 focus:ring-2 focus:ring-[#9C451B] focus:border-[#9C451B] text-sm">
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-stone-300 text-[#9C451B] focus:ring-[#9C451B]">
                        <span class="text-stone-600">Remember session</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3 rounded-lg bg-[#091433] hover:bg-[#394F3D] text-white font-bold text-sm shadow-md transition-colors">
                    Authenticate & Enter &rarr;
                </button>
            </form>

            <div class="pt-4 border-t border-stone-100 text-center text-xs text-stone-400">
                <a href="{{ route('home') }}" class="text-[#9C451B] hover:underline font-semibold">&larr; Return to Public Website</a>
            </div>
        </div>

        <div class="text-center mt-6 text-xs text-stone-500">
            &copy; {{ date('Y') }} Shiv Aaradhana Private Limited. Secured Administration Console.
        </div>
    </div>
</body>
</html>

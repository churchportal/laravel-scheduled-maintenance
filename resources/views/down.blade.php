<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }} | Down for Maintenance</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="antialiased bg-gray-800">
    <div class="h-screen flex justify-center align-middle items-center">
        <div class="max-w-3xl mx-auto flex-1">
            <div class="bg-white overflow-hidden shadow rounded-lg divide-y divide-gray-200">
                <div class="px-4 py-5 sm:px-6">
                    {{ app('maintenance')->current()->title ?? "Well this is awkward..." }}
                </div>

                <div class="px-4 py-5 sm:p-6">
                    {!! app('maintenance')->current()->description !!}

                    @if(app('maintenance')->current()->ends_at)
                        <p class="mt-10">
                            We'll be back up by {{ app('maintenance')->current()->ends_at->format('F jS, \a\t g:ia') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>

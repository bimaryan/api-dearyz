<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API DEARYZ</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">

    <div class="min-h-screen flex justify-center items-center">
        <div class="text-center p-6 bg-white shadow-lg rounded-lg w-1/2">
            <h1 class="text-2xl font-semibold mb-4">Check API Status</h1>

            @if ($apiStatus === 'API is Online')
                <div class="bg-green-100 text-green-600 py-2 px-4 rounded-lg">
                    <i class="fas fa-check-circle"></i> {{ $apiStatus }}
                </div>
            @else
                <div class="bg-red-100 text-red-600 py-2 px-4 rounded-lg">
                    <i class="fas fa-times-circle"></i> {{ $apiStatus }}
                </div>
            @endif

        </div>
    </div>

</body>

</html>

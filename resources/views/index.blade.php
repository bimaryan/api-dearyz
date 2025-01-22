<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API DEARYZ</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(45deg, #f7a8b8, #f7e2ab, #a8d0f7, #a8f7a8);
            background-size: 400% 400%;
            animation: gradientBackground 15s ease infinite;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 25px;
        }

        @keyframes gradientBackground {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .icon {
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.2);
            }

            100% {
                transform: scale(1);
            }
        }
    </style>
</head>

<body>
    <div class="text-center p-6 bg-white rounded-xl shadow-xl max-w-screen-md w-full">
        <h1 class="text-3xl font-semibold text-gray-800 mb-6">Check API Status</h1>
        @if ($apiStatus === 'API is Online')
            <div class="bg-green-100 text-green-600 py-4 px-6 rounded-lg shadow-lg">
                <i class="fas fa-check-circle icon text-4xl mb-4"></i>
                <h2 class="text-xl font-semibold">API is Online</h2>
                <p class="text-sm text-gray-500">The API is functioning normally and ready to use.</p>
            </div>
        @else
            <div class="bg-red-100 text-red-600 py-4 px-6 rounded-lg shadow-lg">
                <i class="fas fa-times-circle icon text-4xl mb-4"></i>
                <h2 class="text-xl font-semibold">API is Offline</h2>
                <p class="text-sm text-gray-500">The API is currently down or unreachable. Please try again later.</p>
            </div>
        @endif
    </div>
</body>

</html>

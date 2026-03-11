
<!DOCTYPE html>
<html lang="{{ str_replace('_','-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Connexion</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

@vite(['resources/css/app.css','resources/js/app.js'])

</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen font-sans">

<div class="w-full max-w-md bg-white p-8 rounded-lg shadow">

<h1 class="text-2xl font-bold text-center text-green-600 mb-6">Connexion</h1>
<p class="text-[13px] text-gray-500 mb-6 text-center">Veuillez vous connecter à votre compte</p>

@if(session('error'))
<div class="bg-red-100 text-red-600 p-3 rounded mb-4 text-sm">
{{ session('error') }}
</div>
@endif

<form method="POST" action="{{ route('login') }}" class="space-y-4">

@csrf

<div>
<label class="block text-sm font-semibold mb-1">Adresse e-mail</label>

<input type="email" name="email" value="{{ old('email') }}" required class="w-full border rounded px-3 py-2" placeholder="Entrez votre e-mail">
</div>

<div>
<label class="block text-sm font-semibold mb-1">Mot de passe</label>

<div class="relative">
<input id="password" type="password" name="password" required class="w-full border rounded px-3 py-2 pr-10" placeholder="••••••••">

<button type="button" onclick="togglePassword('password','eye-icon-password')" class="absolute right-2 top-2 text-gray-500">

<svg id="eye-icon-password" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7"/>
</svg>

</button>
</div></br>


<button type="submit" class="w-full bg-green-600 text-white py-2 rounded font-semibold">Se connecter</button>

</form>

<p class="text-center text-sm mt-4">Nouveau sur SallePro ?
<a href="{{ route('register') }}" class="text-green-600 font-semibold">
Créer un compte
</a>
</p>

</div>
<script>
function togglePassword(inputId, iconId) {

const input = document.getElementById(inputId);
const icon = document.getElementById(iconId);

if (input.type === 'password') {

input.type = 'text';

icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';

} else {

input.type = 'password';

icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7"/>';

}

}
</script>




</body>
</html>


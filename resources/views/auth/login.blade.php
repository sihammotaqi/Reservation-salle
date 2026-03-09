<!DOCTYPE html>
<html>

<head>

<title>Connexion</title>

<link rel="stylesheet" href="{{ asset('css/auth.css') }}">

</head>

<body>

<div class="container">

<div class="card">

<h2>Connexion</h2>

<p class="subtitle">Veuillez vous connecter à votre compte</p>

<form method="POST" action="/login">

@csrf

<div class="input-group">
<label>Adresse e-mail</label>
<input type="email" name="email" placeholder="Entrez votre e-mail">
</div>

<div class="input-group">
<label>Mot de passe</label>
<input type="password" name="password" placeholder="Entrez votre mot de passe">
</div>

<button class="btn">
Se connecter
</button>

</form>

<p class="link">
Nouveau sur SallePro ?
<a href="/register">Créer un compte</a>
</p>

</div>

</div>

</body>
</html>
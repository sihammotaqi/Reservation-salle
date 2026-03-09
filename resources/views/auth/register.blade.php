<!DOCTYPE html>
<html>

<head>

<title>Register</title>

<link rel="stylesheet" href="{{ asset('css/auth.css') }}">

</head>

<body>

<div class="container">

<div class="card">

<h2>Créer un compte</h2>

<p class="subtitle">Rejoignez-nous pour commencer</p>

<form method="POST" action="/register">

@csrf

<div class="input-group">
<label>Nom complet</label>
<input type="text" name="name" placeholder="Votre nom complet">
</div>

<div class="input-group">
<label>Adresse e-mail</label>
<input type="email" name="email" placeholder="example@email.com">
</div>

<div class="input-group">
<label>Mot de passe</label>
<input type="password" name="password" placeholder="Votre mot de passe">
</div>

<div class="input-group">
<label>Confirmer le mot de passe</label>
<input type="password" name="password_confirmation" placeholder="Confirmer votre mot de passe">
</div>

<button type="submit" class="btn">
S'inscrire
</button>

</form>

<p class="link">
Déjà un compte ?
<a href="/login">Se connecter</a>
</p>

</div>

</div>

</body>
</html>
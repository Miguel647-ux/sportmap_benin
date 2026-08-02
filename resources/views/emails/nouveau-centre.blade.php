<!DOCTYPE html>
<html>
<head>
    <title>Nouveau centre en attente</title>
</head>
<body>
    <h2>Nouveau centre ajouté sur SportMap Bénin</h2>
    <p>Un visiteur a ajouté un nouveau centre en attente de validation.</p>

    <p><strong>Nom :</strong> {{ $centre->nom }}</p>
    <p><strong>Commune :</strong> {{ $centre->commune ?? 'Non renseignée' }}</p>
    <p><strong>Disciplines :</strong>
        @foreach ($centre->disciplines as $discipline)
            {{ $discipline->nom }}{{ !$loop->last ? ', ' : '' }}
        @endforeach
    </p>
    <p><strong>Téléphone :</strong> {{ $centre->telephone ?? 'Non renseigné' }}</p>

    <a href="{{ url('/admin/dashboard.html') }}" style="background:#1E88E5;color:#fff;padding:12px 24px;border-radius:8px;text-decoration:none;display:inline-block;">
        Voir dans le dashboard
    </a>

    <hr>
    <p><small>SportMap Bénin — L'annuaire des centres sportifs du Bénin</small></p>
</body>
</html>
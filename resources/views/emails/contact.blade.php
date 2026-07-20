<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background: #f9fafb; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .header { border-bottom: 3px solid #FF6B00; padding-bottom: 15px; margin-bottom: 20px; }
        .header h2 { margin: 0; color: #0A0A0A; }
        .info { background: #f3f4f6; padding: 15px; border-radius: 8px; margin: 15px 0; }
        .info p { margin: 5px 0; }
        .message { background: #f8fafc; padding: 20px; border-radius: 8px; border-left: 4px solid #FF6B00; }
        .footer { margin-top: 25px; font-size: 13px; color: #6b7280; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>📩 Nouveau message de contact</h2>
        </div>

        <div class="info">
            <p><strong>De :</strong> {{ $contact->nom ?? 'Anonyme' }} ({{ $contact->email }})</p>
            <p><strong>Reçu le :</strong> {{ $contact->created_at->format('d/m/Y à H:i') }}</p>
        </div>

        <div class="message">
            <p>{{ $contact->message }}</p>
        </div>

        <div class="footer">
            <p>SportMap Bénin — Plateforme de géolocalisation des centres sportifs</p>
        </div>
    </div>
</body>
</html>
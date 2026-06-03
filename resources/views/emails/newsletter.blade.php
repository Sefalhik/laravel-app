<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $newsletter->subject }}</title>
</head>
<body style="font-family: sans-serif; max-width: 600px; margin: 0 auto; padding: 24px; color: #111827;">

    <h1 style="font-size: 1.5rem; font-weight: bold; margin-bottom: 24px;">
        {{ $newsletter->subject }}
    </h1>

    <div style="line-height: 1.6; white-space: pre-wrap;">
        {{ $newsletter->body }}
    </div>

    <hr style="margin: 32px 0; border: none; border-top: 1px solid #e5e7eb;">

    <p style="font-size: 0.75rem; color: #6b7280;">
        Vous recevez cet email car vous êtes abonné(e) à notre newsletter.
    </p>

</body>
</html>

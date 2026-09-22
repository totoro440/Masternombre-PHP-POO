<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Nombre - Mode Professionnel</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f9; color: #333; max-width: 600px; margin: 40px auto; padding: 20px; border-radius: 8px; background: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h1 { color: #2c3e50; text-align: center; }
        .alert { padding: 10px; border-radius: 4px; margin-bottom: 20px; font-weight: bold; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .info { background: #e2e3e5; color: #383d41; }
        form { display: flex; gap: 10px; margin-bottom: 20px; }
        input[type="text"] { flex: 1; padding: 10px; font-size: 16px; border: 1px solid #ccc; border-radius: 4px; text-align: center; letter-spacing: 5px; font-weight: bold; }
        button { padding: 10px 20px; font-size: 16px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        button:hover { background: #2980b9; }
        .btn-restart { background: #e67e22; width: 100%; }
        .btn-restart:hover { background: #d35400; }
        ul { list-style: none; padding: 0; }
        li { padding: 10px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; }
        .badge { background: #2ecc71; color: white; padding: 2px 8px; border-radius: 12px; font-size: 14px; }
        .badge-miss { background: #f1c40f; color: white; }
    </style>
</head>
<body>

    <h1>🔢 Master Nombre</h1>

    {{-- Affichage des messages d'erreur de saisie du domaine --}}
    @if($errorMsg)
        <div class="alert error">⚠️ {{ $errorMsg }}</div>
    @endif

    {{-- Arbitrage de l'écran par le métier --}}
    @if($game->isOver())
        @if($game->isVictory())
            <div class="alert success">🎉 Félicitations ! Vous avez décodé le Nombre {{$game->getRules()->getSecret()}}!</div>
        @else
            <div class="alert error">💥 Dommage ! Vous avez épuisé toutes vos tentatives. Vous n'avez pas trouvé le Nombre {{$game->getRules()->getSecret()}}</div>
        @endif
        
        <form method="post">
            <button type="submit" name="recommencer" class="btn-restart">Commencer une nouvelle partie</button>
        </form>
    @else
        <div class="alert info">💡 Essais restants : <strong>{{ $game->getRemainingTries() }}</strong></div>
        
        <form method="post" action="/">
            <input type="text" name="proposal" maxlength="5" pattern="[0-9]{5}" required autocomplete="off" autofocus placeholder="12345">
            <button type="submit">Valider</button>
        </form>
    @endif

    @if($game->getClues() !== [])
        @foreach ($game->getClues() as $clue)
        <div style="background-color: #fff3cd; color: #856404; padding: 15px; margin-bottom: 20px; border: 1px solid #ffeeba; border-radius: 4px;">
            💡  {{ $clue }}
        </div>
        @endforeach
    @endif

    {{-- Historique des tentatives calculées par le composant métier --}}
    <h3>📊 Historique des essais</h3>
    @if(empty($game->getHistorique()))
        <p style="color: #7f8c8d; font-style: italic;">Aucune tentative pour le moment. Entrez un nombre ci-dessus !</p>
    @else
        <ul>
            @foreach($game->getHistorique() as $coup)
                <li>
                    <span>Proposition : <strong>{{ $coup['proposal'] }}</strong></span>
                    <div>
                        <span class="badge">Bien placés : {{ $coup['clues']['well_placed'] }}</span>
                        <span class="badge badge-miss">Mal placés : {{ $coup['clues']['is_present'] }}</span>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif

</body>
</html>
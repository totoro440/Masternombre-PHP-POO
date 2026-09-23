<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Nombre</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f4f4f9; 
            color: #333; 
            max-width: 600px; 
            margin: 40px auto; 
            padding: 20px; 
            border-radius: 8px; 
            background: white; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.1); 
        }

        h1 { 
            color: #2c3e50; 
            text-align: center; 
        }

        h3 {
            color: #2c3e50;
            margin-top: 30px;
        }

        .alert { 
            padding: 10px; 
            border-radius: 4px; 
            margin-bottom: 20px; 
            font-weight: bold; 
        }

        .alert.error { 
            background: #f8d7da; 
            color: #721c24; 
            border: 1px solid #f5c6cb; 
        }

        .alert.success { 
            background: #d4edda; 
            color: #155724; 
            border: 1px solid #c3e6cb; 
        }

        .alert.info { 
            background: #e2e3e5; 
            color: #383d41; 
        }

        form { 
            display: flex; 
            gap: 10px; 
            margin-bottom: 20px; 
        }

        input[type="text"] { 
            flex: 1; 
            padding: 10px; 
            font-size: 16px; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
            text-align: center; 
            letter-spacing: 5px; 
            font-weight: bold; 
        }

        button { 
            padding: 10px 20px; 
            font-size: 16px; 
            background: #3498db; 
            color: white; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
            font-weight: bold; 
        }

        button:hover { 
            background: #2980b9; 
        }

        .btn-restart { 
            background: #e67e22; 
            width: 100%; 
        }

        .btn-restart:hover { 
            background: #d35400; 
        }

        ul { 
            list-style: none; 
            padding: 0; 
        }

        li { 
            padding: 10px; 
            border-bottom: 1px solid #eee; 
            display: flex; 
            justify-content: space-between; 
        }

        .badge { 
            background: #2ecc71; 
            color: white; 
            padding: 2px 8px; 
            border-radius: 12px; 
            font-size: 14px; 
            margin-right: 5px;
        }

        .badge.badge-miss { 
            background: #f1c40f; 
        }

        .clue {
            background-color: #fff3cd;
            color: #856404;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ffeeba;
            border-radius: 4px;
        }

        .empty-state {
            color: #7f8c8d;
            font-style: italic;
        }
    </style>
</head>
<body>

    <h1>🔢 Master Nombre</h1>

    {{-- WALL OF FAME --}}
    <div>
        <h3>🏆 Top 3 des Meilleurs Scores</h3>
        @if(empty($topScores))
            <p>Aucun score enregistré pour le moment. Soyez le premier !</p>
        @else
            <ol>
                @foreach($topScores as $line)
                    <li><strong>{{ $line['nom_joueur'] }}</strong> — {{ $line['score'] }} points</li>
                @endforeach
            </ol>
        @endif
    </div>

    {{-- Affichage des messages d'erreur de saisie du domaine --}}
    @if($errorMsg)
        <div class="alert error">⚠️ {{ $errorMsg }}</div>
    @endif

    {{-- Arbitrage de l'écran par le métier --}}
    @if($game->isOver())
        @if($game->isVictory())
            <div class="alert success">🎉 Félicitations ! Vous avez décodé le Nombre {{$game->getRules()->getSecret()}}!</div>
        {{-- Formulaire pour enregistrer le score --}}
        <form method="post" style="margin-bottom: 20px;">
            <label for="nom_joueur">Entrez votre nom pour le tableau des scores :</label>
            <input type="text" name="nom_joueur" id="nom_joueur" required placeholder="Votre pseudo">
            <button type="submit" name="enregistrer_score">Valider mon Score</button>
        </form>
        @else
            <div class="alert error">💥 Dommage ! Vous avez épuisé toutes vos tentatives. Vous n'avez pas trouvé le Nombre {{$game->getRules()->getSecret()}}</div>
        @endif
        
        <form method="post">
            <button type="submit" name="recommencer" class="btn-restart">Commencer une nouvelle partie</button>
        </form>
    @else
    @if($game->getClues() !== [])
        @foreach ($game->getClues() as $clue)
            <div class="clue">💡 {{ $clue }}</div>
        @endforeach
    @endif
        <div class="alert info"> Essais restant :  
    @for ($i = 1; $i <= $game->getRemainingTries() ; $i++)
        ❤️
    @endfor
        </div>    
        <form method="post" action="/">
            <input type="text" name="proposal" maxlength="5" pattern="[0-9]{5}" required autocomplete="off" autofocus placeholder="12345">
            <button type="submit">Valider</button>
        </form>
    @endif


    {{-- Historique des tentatives calculées par le composant métier --}}
    <h3>📊 Historique des essais</h3>
    @if(empty($game->getHistorique()))
        <p class="empty-state">Aucune tentative pour le moment. Entrez un nombre ci-dessus !</p>
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
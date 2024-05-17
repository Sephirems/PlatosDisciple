<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page secrète</title>
    <style>
        /* Centrer le conteneur horizontalement et verticalement */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Centrer le texte et la vidéo dans le conteneur */
        .container {
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bravo tu t'es fait Rick Astley !</h1>
        <div id="player"></div>
    </div>

    <script>
        // Chargement de l'API YouTube
        var tag = document.createElement('script');
        tag.src = 'https://www.youtube.com/iframe_api';
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

        // Fonction appelée lorsque l'API est chargée
        function onYouTubeIframeAPIReady() {
            new YT.Player('player', {
                height: '720',
                width: '1280',
                videoId: 'dQw4w9WgXcQ', // Remplacez par l'ID de votre vidéo
                playerVars: {
                    autoplay: 1, // Lecture automatique
                    controls: 1, // Afficher les contrôles
                    modestbranding: 1 // Masquer le logo YouTube
                }
            });
        }
    </script>
</body>
</html>

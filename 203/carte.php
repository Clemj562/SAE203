<?php
$boutiques = dbquery("SELECT id, nom, numero_rue, nom_adresse, code_postal, ville, pays FROM boutiques");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Boutiques</title>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin=""/>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }
        
        .popup-boutique {
            font-family: Arial, sans-serif;
            max-width: 250px;
        }
        
        .popup-boutique h3 {
            margin: 0 0 10px 0;
            color: #e74c3c;
            font-size: 16px;
            font-weight: bold;
        }
        
        .popup-boutique p {
            margin: 5px 0;
            font-size: 14px;
            line-height: 1.4;
        }
        
        .popup-boutique .adresse {
            color: #7f8c8d;
        }
        
        .boutique-info {
            margin: 20px auto;
            padding: 15px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            max-width: 800px;
            text-align: center;
        }
        
        #map {
            height: 500px;
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
        }
        
        .loading-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 20px auto;
            text-align: center;
            max-width: 800px;
        }
    </style>
</head>
<body>
    <h1>Nos Boutiques de Bonbons</h1>
    
    <?php if (empty($boutiques)): ?>
        <div class="error-message">
            <p>Aucune boutique trouvée dans la base de données.</p>
        </div>
    <?php else: ?>
        <div class="boutique-info">
            <p><strong>Nombre de boutiques :</strong> <?= count($boutiques) ?></p>
            <p>Cliquez sur les bonbons pour voir les détails de chaque boutique</p>
        </div>
        
        <div class="loading-message" id="loading">
            <p>🍭 Chargement des boutiques en cours...</p>
        </div>
        
        <div id="map"></div>
    <?php endif; ?>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>
            
    <script>
        <?php if (!empty($boutiques)): ?>
        // Données des boutiques depuis PHP
        const boutiquesData = <?= json_encode($boutiques) ?>;
        console.log('Données boutiques:', boutiquesData);
        
        // Initialisation de la carte (centré sur la France)
        const map = L.map('map').setView([46.603354, 1.888334], 6);
        const marqueursBoutiques = L.layerGroup().addTo(map);
        
        // Icône bonbon personnalisée
        const iconeBonbon = L.divIcon({
            html: `
                <div style="
                    width: 40px; 
                    height: 40px; 
                    background: linear-gradient(45deg, #ff6b6b, #ff8e8e, #ffb3ba);
                    border-radius: 50% 50% 50% 70% / 60% 60% 80% 40%;
                    border: 3px solid #fff;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 18px;
                    transform: rotate(-15deg);
                    position: relative;
                ">
                    🍬
                    <div style="
                        position: absolute;
                        top: -8px;
                        right: -8px;
                        width: 12px;
                        height: 12px;
                        background: #fff;
                        border-radius: 50%;
                        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
                    "></div>
                </div>
            `,
            className: 'bonbon-marker',
            iconSize: [40, 40],
            iconAnchor: [20, 35],
            popupAnchor: [0, -35]
        });
        
        // Coordonnées fixes pour les villes principales (fallback si géocodage échoue)
        const coordonneesVilles = {
            'Paris': [48.8566, 2.3522],
            'Lyon': [45.7640, 4.8357],
            'Marseille': [43.2965, 5.3698],
            'Toulouse': [43.6047, 1.4442],
            'Nice': [43.7102, 7.2620],
            'Nantes': [47.2184, -1.5536],
            'Strasbourg': [48.5734, 7.7521],
            'Montpellier': [43.6110, 3.8767],
            'Bordeaux': [44.8378, -0.5792],
            'Lille': [50.6292, 3.0573]
        };
        
        // Fonction pour obtenir les coordonnées d'une ville
        function obtenirCoordonnees(boutique) {
            const ville = boutique.ville;
            if (coordonneesVilles[ville]) {
                return coordonneesVilles[ville];
            }
            return null;
        }
        
        // Fonction pour géocoder une adresse avec l'API Nominatim
        async function geocoderAdresse(boutique) {
            try {
                // Essayer d'abord avec l'adresse complète
                const adresseComplete = `${boutique.numero_rue} ${boutique.nom_adresse}, ${boutique.code_postal} ${boutique.ville}, ${boutique.pays}`;
                console.log(`Géocodage: ${adresseComplete}`);
                
                const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(adresseComplete)}&limit=1&addressdetails=1`);
                const data = await response.json();
                
                if (data.length > 0) {
                    const lat = parseFloat(data[0].lat);
                    const lon = parseFloat(data[0].lon);
                    console.log(`Coordonnées trouvées pour ${boutique.nom}: ${lat}, ${lon}`);
                    return [lat, lon];
                } else {
                    // Si pas de résultat, essayer juste avec la ville
                    console.log(`Adresse complète non trouvée, essai avec la ville: ${boutique.ville}`);
                    const responseVille = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(boutique.ville + ', ' + boutique.pays)}&limit=1`);
                    const dataVille = await responseVille.json();
                    
                    if (dataVille.length > 0) {
                        const lat = parseFloat(dataVille[0].lat);
                        const lon = parseFloat(dataVille[0].lon);
                        console.log(`Coordonnées ville trouvées pour ${boutique.nom}: ${lat}, ${lon}`);
                        return [lat, lon];
                    } else {
                        // Utiliser les coordonnées fixes comme dernier recours
                        const coords = obtenirCoordonnees(boutique);
                        if (coords) {
                            console.log(`Utilisation coordonnées fixes pour ${boutique.nom}: ${coords}`);
                            return coords;
                        }
                    }
                }
                
                console.warn(`Aucune coordonnée trouvée pour: ${boutique.nom}`);
                return null;
            } catch (error) {
                console.error(`Erreur lors du géocodage de ${boutique.nom}:`, error);
                // Utiliser les coordonnées fixes en cas d'erreur
                const coords = obtenirCoordonnees(boutique);
                if (coords) {
                    console.log(`Utilisation coordonnées fixes après erreur pour ${boutique.nom}: ${coords}`);
                    return coords;
                }
                return null;
            }
        }
        
        // Fonction pour ajouter un marqueur
        function ajouterMarqueur(boutique, coords) {
            const [lat, lon] = coords;
            
            // Contenu du popup
            const popupContent = `
                <div class="popup-boutique">
                    <h3>🍭 ${boutique.nom}</h3>
                    <p class="adresse">
                        📍 ${boutique.numero_rue} ${boutique.nom_adresse}<br>
                        ${boutique.code_postal} ${boutique.ville}<br>
                        ${boutique.pays}
                    </p>
                    <p style="color: #e74c3c; font-weight: bold;">Votre boutique de bonbons préférée !</p>
                </div>
            `;
            
            // Création du marqueur
            const marqueur = L.marker([lat, lon], {icon: iconeBonbon})
                .bindPopup(popupContent);
            marqueursBoutiques.addLayer(marqueur);
            
            console.log(`Marqueur ajouté pour ${boutique.nom} à [${lat}, ${lon}]`);
        }
        
        // Charger toutes les boutiques
        async function chargerToutesBoutiques() {
            // Ajouter la couche de tuiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
            
            let boutiquesAjoutees = 0;
            
            // Traiter chaque boutique
            for (let i = 0; i < boutiquesData.length; i++) {
                const boutique = boutiquesData[i];
                console.log(`Traitement de: ${boutique.nom}`);
                
                const coords = await geocoderAdresse(boutique);
                if (coords) {
                    ajouterMarqueur(boutique, coords);
                    boutiquesAjoutees++;
                }
                
                // Délai entre les requêtes pour respecter les limites de l'API
                if (i < boutiquesData.length - 1) {
                    await new Promise(resolve => setTimeout(resolve, 1200));
                }
            }
            
            // Masquer le message de chargement
            document.getElementById('loading').style.display = 'none';
            
            // Ajuster la vue pour montrer toutes les boutiques
            if (boutiquesAjoutees > 0) {
                const group = new L.featureGroup(marqueursBoutiques.getLayers());
                if (group.getLayers().length > 0) {
                    map.fitBounds(group.getBounds().pad(0.1));
                }
                console.log(`${boutiquesAjoutees} boutiques affichées sur la carte`);
            } else {
                console.error('Aucune boutique n\'a pu être géolocalisée');
                // Afficher un message d'erreur
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message';
                errorDiv.innerHTML = '<p>Erreur: Impossible de localiser les boutiques sur la carte.</p>';
                document.body.appendChild(errorDiv);
            }
        }
        
        // Initialiser la carte quand la page est chargée
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Initialisation de la carte...');
            chargerToutesBoutiques();
        });
        
        <?php else: ?>
        console.log('Aucune boutique à afficher');
        <?php endif; ?>
    </script>
</body>
</html>
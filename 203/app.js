/* =================== */
/* GET DATA */
/* =================== */

// Reference : https://developer.mozilla.org/en-US/docs/Learn/JavaScript/Objects/JSON
// Request : https://datarmor.cotesdarmor.fr/datasets/arbres-remarquables-des-cotes-d'armor/api-doc

/*
Call The API an return the remarquable trees of Côtes-d'Armor

*/

async function getTrees() {
    const requestURL =
        "https://datarmor.cotesdarmor.fr/data-fair/api/v1/datasets/arbres-remarquables-des-cotes-d'armor/lines?size=1000&q=typearbre=remarquable"; // Fournir l'url
    const request = new Request(requestURL)

    const response = await fetch(request)
    const respJSON = await response.json() // Fournir la fonction jusque-là ?

    const trees = respJSON.results

    return trees
}

/* The trees from the API */
const TREES = await getTrees()

console.log(TREES)


var map = L.map('map').setView([48.6, -2.8], 9);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

var icontree = L.icon({
    iconUrl: 'images/marker.png',

    iconSize: [38, 60],
    iconAnchor: [19, 60],
    popupAnchor: [0, -62],
});

function info(tree){
    const block = document.querySelector('.tree-focus');
  
    const name = document.createElement('h3');
    name.classList.add('Essence');
    name.textContent = tree.Essence; 
    block.appendChild(name);
  
    const location = document.createElement('h4');
    location.classList.add('Communes');
    location.textContent = tree.Commune; 
    block.appendChild(location);

    const size = document.createElement('p');
    size.classList.add('Dimensioncirconférence');
    size.textContent = "Circonférence :" + " " + tree.Dimensioncirconference;
    block.appendChild(size);

    const height = document.createElement('p');
    height.classList.add('Envergure');
    height.textContent = "Envergure :" + " " + tree.dimensionenvergure;
    block.appendChild(height);
}


for (let i = 0; i < TREES.length; i++) {
    const tree = TREES[i];
    if (tree._geopoint) {
        const coord = tree._geopoint.split(',');
        const marker = L.marker(coord, { icon: iconcandy }).addTo(map);

        marker.on('click', () => {
            document.querySelector(".tree-focus").innerHTML = "";
            info(tree); 
        });
    }
}
<style>
    /**FOOTER**/

footer {
  background-color: rgb(140, 191, 227);
  padding: 3.5rem 0rem;
  font-family: sans-serif;
  display: flex;
  flex-direction: column;
  color: rgb(255, 255, 255);
  margin-bottom: 0;
}

.footer-container {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  flex-wrap: wrap;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 2rem;
}

.footer-container .logo-circle {
  width: 200px;
  height: 200px;
  background-color: var(--fourth-color);
  border-radius: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
  text-align: center;
  object-fit: cover;
}

.footer-center {
  display: flex;
  justify-content: space-around;
  gap: 4rem;
  flex: 1;
  margin-left: 4rem;
}

.footer-center h3 {
  margin-bottom: 1.5rem;
  font-weight: bold;
}

.footer-center ul {
  list-style: none;
  padding: 0;
}

.footer-center li {
  margin-bottom: 0.5rem;
  display: flex;
  align-items: center;
}

.footer-center a {
  text-decoration: none;
  color: rgb(255, 255, 255);
}

.footer-center form {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.footer-center input,
.footer-center button {
  border: none;
  border-radius: 10px;
  padding: 0.6rem;
}

.footer-center input[type="text"] {
  background-color: white;
}

.footer-center input[type="email"] {
  background-color: var(--fourth-color);
}

.footer-center button {
  background-color: var(--third-color);
  color: white;
  cursor: pointer;
}

.footer-center img {
  width: 35px;
  height: 35px;
  margin-right: 1rem;
}

.footer-bottom {
  margin-top: 2rem;
  text-align: center;
}

.footer-bottom hr {
  border: none;
  border-top: 1.2px solid black;
  width: 65%;
}

.footer-bottom p {
  margin-top: 1.5rem;
}
</style>

<div class="footer-container">
  <img src="./media/img/logo.jpg" class="logo-circle" alt="Logo du site">
  <div class="footer-center">
    <div>
      <h3>Navigation</h3>
      <ul>
        <li><a href="#">Accueil</a></li>
        <li><a href="#">À propos</a></li>
        <li><a href="#">Services</a></li>
      </ul>
    </div>
    <div>
      <h3>Réseaux Sociaux</h3>
      <ul>
        <li><img src="./media/img/instagram.png"><a href="#">Instagram</a></li>
        <li><img src="./media/img/linkedin.png"><a href="#">LinkedIn</a></li>
        <li><img src="./media/img/discorde.png"><a href="#">Discord</a></li>
      </ul>
    </div>
    <div>
      <h3>Contact</h3>
      <form>
        <input type="text" placeholder="Votre message" />
        <input type="email" placeholder="Votre adresse e-mail" />
        <button type="submit">Envoyer</button>
      </form>
    </div>
  </div>
</div>

<div class="footer-bottom">
  <hr />
  <p>Confiz © 2025 - Tous droits réservés - Mentions légales</p>
</div>

</body>
</html>
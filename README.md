<h1>TNote</h1>

<p>Telepítés (clone-ozás után, /TNoteBackend mappában):</p>
<ul> 
    <li><span style="color: yellow">composer</span> install</li>
    <li><span style="color: yellow">npm</span> install</li>
    <li><span style="color: yellow">npm</span> run dev</li>
</ul>
<p>Backend server futtatása (/TNoteBackend mappában):</p>
<ul>
    <li>php artisan migrate --seed</li>
    <li>php artisan serve</li>
</ul>
<p>Az Api használatánál ne felejtsük el beallítani az "Accept" és "Content-Type" header-t "application/json"-re</p>

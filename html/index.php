<!DOCTYPE html>
<html lang="pt-BR">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>MedTime</title>

<link rel="stylesheet" href="style.css">
<style> 
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Inter',sans-serif;
}

.container{

width:90%;
max-width:1200px;
margin:auto;

}



/* HEADER */

header{

height:90px;
background:white;
border-bottom:1px solid #eee;

}


header .container{

height:100%;
display:flex;
align-items:center;
justify-content:space-between;

}

.hero{

padding:80px 0;

}



.hero .container{

display:flex;
align-items:center;
justify-content:space-between;
gap:50px;

}



.texto{

max-width:600px;

}



.hero h5{

display:inline-block;
background:#ffe1e4;
color:#e50914;

padding:10px 20px;

border-radius:20px;

margin-bottom:25px;

}



.hero h2{

font-size:60px;
line-height:1.1;

font-weight:800;

}



.hero h2 span{

color:#e50914;

}



.hero p{

margin-top:25px;

font-size:20px;

color:#555;

line-height:1.5;

}



button{

margin-top:30px;

background:#e50914;

color:white;

border:none;

padding:16px 35px;

border-radius:12px;

font-size:17px;

font-weight:600;

cursor:pointer;

}



button:hover{

background:#b8000c;

}




.imagem img{

width:500px;

height:500px;

object-fit:cover;

border-radius:30px;

}


.estatisticas{

background:white;

padding:70px 0;

}



.estatisticas .container{

display:flex;

justify-content:space-around;

text-align:center;

}



.estatisticas h3{

font-size:45px;

color:#e50914;

}




/* CARDS */


.funcionalidades{

padding:80px 0;

text-align:center;

}



.funcionalidades h2{

font-size:40px;

}



.subtitulo{

color:#666;

margin:15px;

}




.cards{

display:grid;

grid-template-columns:repeat(3,1fr);

gap:30px;

margin-top:40px;

}



.card{

background:white;

padding:35px;

border-radius:20px;

box-shadow:0 10px 30px #0002;

}



.card h4{

color:#e50914;

margin-bottom:15px;

}





/* CTA */


.cta{

width:90%;

margin:60px auto;

padding:60px;

background:#e50914;

color:white;

text-align:center;

border-radius:30px;

}



.cta button{

background:white;

color:#e50914;

}





/* FOOTER */


footer{

background:#111;

color:white;

padding:30px;

text-align:center;

}




@media(max-width:900px){


.hero .container{

flex-direction:column;

text-align:center;

}



.hero h2{

font-size:40px;

}



.imagem img{

width:90%;

height:auto;

}



.cards{

grid-template-columns:1fr;

}



.container{

flex-direction:column;

gap:30px;

}


}
</style>

</head>

<body>

<header>
    <div class="container">
        <?php require_once "cabecalho.php"; ?>
    </div>
</header>


<main>

<div class="hero">

    <div class="container">

        <div class="texto">

            <h5>Cuide da sua saúde com facilidade</h5>

            <h2>
                Gerencie seus 
                <span>medicamentos</span>
                de forma simples
            </h2>

            <p>
                O MedTime ajuda você a manter o controle da sua medicação,
                garantindo que cada dose seja tomada no momento certo.
            </p>


            <button>
                Começar Agora →
            </button>

        </div>


        <div class="imagem">

            <img src="foto/remediocapa.png" 
            alt="Imagem medicamentos">

        </div>


    </div>

</div>



<div class="estatisticas">

<div class="container">


<div>
<h3>98%</h3>
<p>Taxa de Adesão</p>
</div>


<div>
<h3>24/7</h3>
<p>Monitoramento</p>
</div>


<div>
<h3>100%</h3>
<p>Seguro</p>
</div>


</div>

</div>




<div class="funcionalidades">

<div class="container">


<h2>
Por que escolher o MedTime?
</h2>


<p class="subtitulo">
Funcionalidades pensadas para sua saúde
</p>



<div class="cards">


<div class="card">

<h4>Lembretes Inteligentes</h4>

<p>
Nunca esqueça seus medicamentos
com notificações personalizadas.
</p>

</div>



<div class="card">

<h4>Calendário de Doses</h4>

<p>
Visualize sua programação de medicamentos
em um só lugar.
</p>

</div>




<div class="card">

<h4>Seguro e Privado</h4>

<p>
Seus dados de saúde permanecem protegidos.
</p>

</div>


</div>


</div>

</div>




<div class="cta">

<h2>
Pronto para começar?
</h2>


<p>
Cuide melhor da sua saúde com o MedTime.
</p>


<button>
<a href="login.php">Cadastre</a>
</button>


</div>



</main>



<footer>

<div class="container">

<?php require_once "rodape.php"; ?>

</div>

</footer>


</body>

</html>
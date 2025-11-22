<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env("APP_NAME") }}</title>
    <style>
        @import url(https://api.fonts.coollabs.io/css?family=Raleway:300,900);
@import url('https://fonts.googleapis.com/css?family=Lato:300,400|Poppins:300,400,800&display=swap');

* {
  margin: 0;
  padding: 0;
}

body, html {
   overflow: hidden;
}

.container {
  width: 100%;
  height: 100vh;
  background: #232323;

  display: flex;
  justify-content: center;
  align-items: center;  
}

.box {
    width: 250px;
    /* height: 280px; */
    padding: 30px;
    position: relative;
    display: flex;
    justify-content: center;
    flex-direction: column;
    border: 1px solid firebrick;
    position: relative;
    border-radius: 20px;
}

.title {
  width: 100%;
  position: relative;
/*   display: flex;
  align-items: center; */
  height: 70px;  
}

.title .block {
    width: 0%;
    height: inherit;
    background: #ffb510;
    position: absolute;
    animation: mainBlock 2s cubic-bezier(.74, .06, .4, .92) forwards;
    display: flex;  
    border-radius: 20px;

}

a {
  text-decoration: none;
}

.title a h1 {
  display: grid;
  font-family: 'Raleway', sans-serif;
  font-weight: 900;
  text-transform: uppercase;
  color: #fff;
  line-height: .85;
  position: relative;
  z-index: 2;
  grid-column: 1 / 2;
  align-self: start;
  justify-self: start;
  gap: .25rem;
  margin-bottom: .3rem;
  margin-left: .5rem; 
          opacity: 0;
        -webkit-animation: mainFadeIn 2s forwards;
        -o-animation: mainFadeIn 2s forwards;
        animation: mainFadeIn 2s forwards;
        animation-delay: 1.6s;  
}

.role {
  width: 100%;
  position: relative;
  display: flex;
  align-items: center;
  height: 30px;
}

.role .block {
  width: 0%;
  height: inherit;
  background: #e91e63;
  position: absolute;
  animation: secBlock 2s cubic-bezier(.74, .06, .4, .92) forwards;
  animation-delay: 2s;
  display: flex;  
    border-radius: 20px;

}

.role p {
  animation: secFadeIn 2s forwards;
  animation-delay: 3.2s;
  opacity: 0;
  font-weight: 400;
  font-family: 'Lato';
  color: #ffffff;
  font-size: 12px;
  text-transform: uppercase;
  letter-spacing: 5px;  
  margin-left: .5rem; 
}




@keyframes mainBlock {
  0% {
    width: 0%;
    left: 0;

  }
  50% {
    width: 100%;
    left: 0;

  }
  100% {
    width: 0;
    left: 100%;
  }
}

@keyframes secBlock {
  0% {
    width: 0%;
    left: 0;

  }
  50% {
    width: 100%;
    left: 0;

  }
  100% {
    width: 0;
    left: 100%;
  }
}

@keyframes mainFadeIn {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}


@keyframes popIn {
  0% {
    width: 0px;
    height: 0px;
    background: #e9d856;
    border: 0px solid #ddd;
    opacity: 0;
  }
  50% {
    width: 10px;
    height: 10px;
    background: #e9d856;
    opacity: 1;
    bottom: 45px;
  }
   65% {
      width: 7px;
    height: 7px;
      bottom: 0px;
      width: 15px
   }
   80% {
      width: 10px;
    height: 10px;
      bottom: 20px
   }
  100% {
    width: 7px;
    height: 7px;
    background: #e9d856;
    border: 0px solid #222;
    bottom: 13px;

  }
}

@keyframes secFadeIn {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 0.5;
  }
}
    </style>
</head>
<body>
<div class="container">
    <div class="box">

        <div class="title">
            <span class="block"></span>
            <a href="https://github.com/Arash-hqz" target="_blank" rel="noopener noreferrer">
              <h1>
                <span>ARASH</span>
                <span>HOSSINZADE</span>             
              </h1>
            </a>
        </div>

        <div class="role">
            <div class="block"></div>
            <p>Powered by</p>
        </div>

    </div>
</div>
</body>
</html>

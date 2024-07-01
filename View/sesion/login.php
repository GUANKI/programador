<style>
    .gradient-custom-2 {
      position: relative;
      overflow: hidden;
    }

    .gradient-custom-2::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('Assets/img/loginbg.jpg') no-repeat center center;
      background-size: cover;
      opacity: 0.6; /* Transparencia inicial del 60% */
      animation: backgroundFade 10s infinite; /* Animación de opacidad */
      z-index: 1;
    }

    .button-login {
      background: linear-gradient(to right, #004d00, #008000);
      border: none;
    }

    .gradient-custom-2 .text-white {
      position: relative;
      z-index: 2;
    }

    .slide-left {
      animation: slideInLeft 1s forwards;
    }

    .slide-right {
      animation: slideInRight 1s forwards;
    }

    @keyframes slideInLeft {
      from {
        transform: translateX(-100%);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    @keyframes slideInRight {
      from {
        transform: translateX(100%);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    @keyframes backgroundFade {
      0%, 100% {
        opacity: 0.9;
      }
      50% {
        opacity: 1;
      }
    }
  </style>
</head>
<body>
<section class="h-100 gradient-form" style="background-color: #eee;">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-xl-10">
          <div class="card rounded-3 text-black">
            <div class="row g-0">
              <div class="col-lg-6 slide-left">
                <div class="card-body p-md-5 mx-md-4">
                  <div class="text-center">
                    <img src="Assets/img/logo.png" style="width: 90px;" alt="logo">
                    <h4 class="mt-1 mb-5 pb-1">Ingreso al Sistema</h4>
                  </div>
                  <form id="login-form" method="POST" action="?c=usuario&a=login">
                    <p>Por Favor Ingrese su Usuario</p>
                    <div data-mdb-input-init class="form-outline mb-4">
                      <input type="text" id="email" name="email" class="form-control" placeholder=""/>
                    </div>
                    <div data-mdb-input-init class="form-outline mb-4">
                      <label class="form-label" for="password">Contraseña</label>
                      <input type="password" id="password" name="password" class="form-control" />
                    </div>
                    <div class="text-center pt-1 mb-5 pb-1">
                      <button data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block fa-lg button-login mb-3" type="submit">Ingresar</button>
                    </div>
                  </form>
                </div>
              </div>
              <div class="col-lg-6 d-flex align-items-center gradient-custom-2 slide-right">
                <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                  <h4 class="mb-4 text-white">PROGRAMADOR CDATH</h4>
                  <p class="small mb-0">Apartado reservado unicamente para administradores del sistema.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
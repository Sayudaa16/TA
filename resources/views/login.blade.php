<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>TI - Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    body {
      background: radial-gradient(circle at center, #121212 0%, #000000 100%);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      color: #fff;
    }
    .login-box {
      background: rgba(255, 255, 255, 0.05);
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 0 20px rgba(0, 255, 255, 0.3);
      width: 100%;
      max-width: 400px;
      backdrop-filter: blur(10px);
    }
    .login-box h2 {
      text-align: center;
      margin-bottom: 30px;
      font-weight: bold;
    }
    .form-control {
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
      color: #fff;
    }
    .form-control:focus {
      background: rgba(255, 255, 255, 0.15);
      box-shadow: none;
      border-color: #0ff;
    }
    .btn-primary {
      background-color: #00bcd4;
      border: none;
    }
    .btn-primary:hover {
      background-color: #0097a7;
    }
    .logo {
      display: block;
      margin: 0 auto 20px auto;
      width: 120px;
    }
  </style>
</head>
<body>

  <div class="login-box">
    <img src="THREAT.png" alt="TI" class="logo"/>
    {{-- <h2>Sign In</h2> --}}

    @if ($errors->any())
      <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
          <p>{{ $error }}</p>
        @endforeach
      </div>
    @endif

    <form method="POST" action="{{ route('login.submit') }}">
      @csrf
      <div class="form-floating mb-3">
        <input type="text" class="form-control" id="floatingInput" placeholder="Username or Email" name="email">
        <label for="floatingInput">Email</label>
      </div>

      <div class="form-floating mb-3">
        <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password">
        <label for="floatingPassword">Password</label>
      </div>

      <button class="btn btn-primary w-100 mb-3">Login</button>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

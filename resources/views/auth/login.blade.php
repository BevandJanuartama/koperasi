<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f3f4f6;
    }
    .pin-input-box {
      width: 48px;
      height: 48px;
      text-align: center;
      font-size: 1.25rem;
      line-height: 1.75rem;
      color: #1f2937;
    }
    .pin-input-box:focus {
      border-color: #4f46e5;
      box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.4);
    }
  </style>
</head>

<body class="flex items-center justify-center min-h-screen p-4">
  <div class="w-full max-w-md bg-white p-6 md:p-8 shadow-2xl rounded-xl">

    <!-- Logo -->
    <div class="flex justify-center mb-6">
      <svg class="w-12 h-12 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
        <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM5 9a1 1 0 000 2h10a1 1 0 100-2H5z"></path>
      </svg>
    </div>

    <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Log In to Your Account</h1>

    <!-- Pesan error dari Laravel -->
    @if ($errors->any())
      <div class="p-3 mb-4 rounded-lg bg-red-100 text-red-700 text-sm font-medium">
        {{ $errors->first() }}
      </div>
    @endif

    <!-- Form login -->
    <form method="POST" action="{{ route('login') }}" id="login-form">
      @csrf

      <!-- Username -->
      <div>
        <label for="username" class="block font-medium text-sm text-gray-700">User ID (Number Only)</label>
        <input id="username"
          class="block mt-1 w-full py-3 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition"
          type="tel"
          inputmode="numeric"
          pattern="\d*"
          name="username"
          value="{{ old('username') }}"
          required
          autofocus
          autocomplete="off"
          placeholder="Enter your numeric User ID"
          oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
      </div>

      <!-- PIN (6 digits) -->
      <div class="mt-4">
        <label for="combined_pin" class="block font-medium text-sm text-gray-700">6-Digit PIN</label>
        <input type="hidden" name="password" id="combined_pin" required />
        <div class="flex space-x-2 mt-1 justify-center sm:justify-start" id="pin-inputs-container"></div>
      </div>

      <!-- Remember Me -->
      <div class="block mt-4">
        <label for="remember_me" class="inline-flex items-center cursor-pointer">
          <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
          <span class="ms-2 text-sm text-gray-600 select-none">Remember me</span>
        </label>
      </div>

      <!-- Tombol login -->
      <div class="flex items-center justify-end mt-6">
        <button type="submit"
          class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition shadow-md">
          Log in
        </button>
      </div>
    </form>
  </div>

  <script>
    const NUM_DIGITS = 6;
    const container = document.getElementById('pin-inputs-container');
    const combinedPinInput = document.getElementById('combined_pin');

    function createPinInputs() {
      for (let i = 0; i < NUM_DIGITS; i++) {
        const input = document.createElement('input');
        input.type = 'password';
        input.id = `pin-${i}`;
        input.maxLength = 1;
        input.inputMode = 'numeric';
        input.pattern = '[0-9]';
        input.placeholder = '•';
        input.classList.add('pin-input-box', 'border', 'border-gray-300', 'rounded-lg', 'focus:ring-2', 'focus:ring-indigo-500', 'focus:border-indigo-500', 'transition', 'duration-200', 'bg-white');
        input.addEventListener('input', (e) => handlePinInput(e.target, i));
        input.addEventListener('keydown', (e) => handleKeyDown(e, i));
        container.appendChild(input);
      }
      document.getElementById('pin-0').focus();
    }

    function handlePinInput(currentInput, index) {
      currentInput.value = currentInput.value.replace(/[^0-9]/g, '').slice(0, 1);
      if (currentInput.value.length === 1 && index < NUM_DIGITS - 1) {
        document.getElementById(`pin-${index + 1}`).focus();
      } else if (currentInput.value.length === 1 && index === NUM_DIGITS - 1) {
        document.querySelector('button[type="submit"]').focus();
      }
      updateCombinedPin();
    }

    function handleKeyDown(e, index) {
      const currentInput = e.target;
      if (e.key === 'Backspace') {
        e.preventDefault();
        if (currentInput.value !== '') {
          currentInput.value = '';
        } else if (index > 0) {
          const previousInput = document.getElementById(`pin-${index - 1}`);
          previousInput.focus();
          previousInput.value = '';
        }
        updateCombinedPin();
      }
    }

    function updateCombinedPin() {
      let fullPin = '';
      for (let i = 0; i < NUM_DIGITS; i++) {
        const input = document.getElementById(`pin-${i}`);
        if (input) fullPin += input.value;
      }
      combinedPinInput.value = fullPin;
    }

    document.addEventListener('DOMContentLoaded', createPinInputs);
  </script>
</body>
</html>

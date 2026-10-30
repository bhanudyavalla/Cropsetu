
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cropsetu - Connecting Farmers & Buyers</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
</head>
<body class="bg-green-50 font-sans text-gray-800">

  <!-- Navbar -->
  <nav class="bg-green-700 text-white px-8 py-4 flex justify-between items-center shadow-md">
    <h1 class="text-3xl font-bold hover:text-yellow-300 transition-colors duration-300">Cropsetu</h1>
    <div class="space-x-6 hidden md:flex">
      <a href="#home" class="hover:text-yellow-300 transition-colors duration-300">Home</a>
      <a href="#about" class="hover:text-yellow-300 transition-colors duration-300">About</a>
      <a href="#features" class="hover:text-yellow-300 transition-colors duration-300">Features</a>
      <a href="#tips" class="hover:text-yellow-300 transition-colors duration-300">Tips</a>
      <a href="#contact" class="hover:text-yellow-300 transition-colors duration-300">Contact</a>
      <a href="login.php" class="hover:text-yellow-300 transition-colors duration-300">
        <i class="fas fa-tractor w-5"></i><span class="ml-2">Login</span>
      </a>
    </div>
  </nav>

  <!-- Hero Section -->
  <section id="home" class="min-h-screen bg-gradient-to-r from-green-700 via-green-600 to-green-500 flex items-center px-8 py-16 relative overflow-hidden">
    <div class="max-w-7xl mx-auto w-full grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

      <!-- Left Content -->
      <div class="text-white z-10">
        <p class="uppercase tracking-widest text-yellow-300 font-semibold mb-4 hover:text-yellow-400 transition-colors duration-300"
           data-aos="fade-up" data-aos-delay="100">
          Smart Agriculture Platform
        </p>

        <h1 class="text-5xl lg:text-6xl font-bold leading-tight mb-6">
          <span class="block" data-aos="fade-up" data-aos-delay="200">Connecting Farmers</span>
          <span class="block" data-aos="fade-up" data-aos-delay="400">Directly With Buyers</span>
        </h1>

        <p class="text-lg text-green-100 mb-8 leading-relaxed hover:text-green-200 transition-colors duration-300">
          <span class="block" data-aos="fade-up" data-aos-delay="500">Cropsetu helps farmers sell their crops directly to buyers without middlemen.</span>
          <span class="block" data-aos="fade-up" data-aos-delay="700">Buyers can find fresh produce easily, while farmers receive fair pricing, seasonal guidance, and better opportunities.</span>
        </p>

        <div class="space-y-4 mb-10">
          <div class="flex items-center gap-3 hover:text-yellow-300 transition-colors duration-300" data-aos="fade-up" data-aos-delay="800">
            <div class="w-3 h-3 bg-yellow-300 rounded-full"></div>
            <p>Direct farmer-to-buyer connection</p>
          </div>
          <div class="flex items-center gap-3 hover:text-yellow-300 transition-colors duration-300" data-aos="fade-up" data-aos-delay="900">
            <div class="w-3 h-3 bg-yellow-300 rounded-full"></div>
            <p>Fair crop pricing without middlemen</p>
          </div>
          <div class="flex items-center gap-3 hover:text-yellow-300 transition-colors duration-300" data-aos="fade-up" data-aos-delay="1000">
            <div class="w-3 h-3 bg-yellow-300 rounded-full"></div>
            <p>Seasonal crop guidance and farming tips</p>
          </div>
        </div>
      </div>

      <!-- Right Register Button with Dora & Bujji -->
      <div class="flex justify-center lg:justify-end relative z-10 w-full max-w-lg mx-auto">
        <img src="images/dora_pointing1.png" alt="Dora pointing" class="absolute -left-0 top-1/2 -translate-y-1/2 w-56 h-56 object-contain" data-aos="fade-right" data-aos-delay="1100">
        <img src="images/bujji_pointing1.png" alt="Bujji pointing" class="absolute -right-32 top-1/2 -translate-y-1/2 w-56 h-56 object-contain" data-aos="fade-left" data-aos-delay="1100">

     
        <a href="register.php"
           class="block bg-white text-green-700 font-extrabold text-3xl py-6 px-16 rounded-3xl shadow-2xl text-center transform transition-all hover:scale-105 hover:shadow-[0_0_25px_rgba(255,0,0,0.5)]">


          Register Now
        </a>
      </div>

    </div>
  </section>

  <!-- About Section -->
  <section id="about" class="py-20 px-8 bg-white">
    <div class="max-w-6xl mx-auto text-center">
      <h2 class="text-4xl font-bold text-green-700 mb-6 hover:text-yellow-400 transition-colors duration-300">How Cropsetu Works</h2>
      <p class="text-lg text-gray-600 max-w-4xl mx-auto leading-relaxed mb-12 hover:text-green-900 transition-colors duration-300">
        Cropsetu is built to create a strong connection between farmers and buyers. Farmers can showcase their crops, get seasonal guidance, and receive fair pricing. Buyers can directly access fresh produce, compare options, and place orders easily.
      </p>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-green-50 p-8 rounded-2xl shadow-md hover:scale-105 hover:shadow-lg transition-transform duration-300" data-aos="fade-up">
          <h3 class="text-2xl font-bold text-green-700 mb-4 hover:text-yellow-400 transition-colors duration-300">For Farmers</h3>
          <p class="text-gray-600 hover:text-green-900 transition-colors duration-300">Sell crops directly, receive better profits, and get seasonal recommendations.</p>
        </div>
        <div class="bg-green-50 p-8 rounded-2xl shadow-md hover:scale-105 hover:shadow-lg transition-transform duration-300" data-aos="fade-up" data-aos-delay="100">
          <h3 class="text-2xl font-bold text-green-700 mb-4 hover:text-yellow-400 transition-colors duration-300">For Buyers</h3>
          <p class="text-gray-600 hover:text-green-900 transition-colors duration-300">Find fresh produce, connect with trusted farmers, and place orders easily.</p>
        </div>
        <div class="bg-green-50 p-8 rounded-2xl shadow-md hover:scale-105 hover:shadow-lg transition-transform duration-300" data-aos="fade-up" data-aos-delay="200">
          <h3 class="text-2xl font-bold text-green-700 mb-4 hover:text-yellow-400 transition-colors duration-300">Direct Connection</h3>
          <p class="text-gray-600 hover:text-green-900 transition-colors duration-300">No middlemen, more transparency, and smoother communication.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section id="features" class="py-20 px-8 bg-green-100">
    <div class="max-w-6xl mx-auto text-center">
      <h2 class="text-4xl font-bold text-green-700 mb-12 hover:text-yellow-400 transition-colors duration-300">Why Choose Cropsetu?</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <div class="bg-white rounded-2xl shadow-md p-8 hover:scale-105 hover:shadow-lg transition-transform duration-300" data-aos="fade-up">
          <h3 class="text-xl font-bold mb-3 text-green-700 hover:text-yellow-400 transition-colors duration-300">Fair Pricing</h3>
          <p class="text-gray-600 hover:text-green-900 transition-colors duration-300">Transparent pricing helps farmers get better profits.</p>
        </div>
        <div class="bg-white rounded-2xl shadow-md p-8 hover:scale-105 hover:shadow-lg transition-transform duration-300" data-aos="fade-up" data-aos-delay="100">
          <h3 class="text-xl font-bold mb-3 text-green-700 hover:text-yellow-400 transition-colors duration-300">Seasonal Tips</h3>
          <p class="text-gray-600 hover:text-green-900 transition-colors duration-300">Know which crops are best for each season.</p>
        </div>
        <div class="bg-white rounded-2xl shadow-md p-8 hover:scale-105 hover:shadow-lg transition-transform duration-300" data-aos="fade-up" data-aos-delay="200">
          <h3 class="text-xl font-bold mb-3 text-green-700 hover:text-yellow-400 transition-colors duration-300">Easy Communication</h3>
          <p class="text-gray-600 hover:text-green-900 transition-colors duration-300">Buyers and farmers can connect quickly and directly.</p>
        </div>
        <div class="bg-white rounded-2xl shadow-md p-8 hover:scale-105 hover:shadow-lg transition-transform duration-300" data-aos="fade-up" data-aos-delay="300">
          <h3 class="text-xl font-bold mb-3 text-green-700 hover:text-yellow-400 transition-colors duration-300">Better Opportunities</h3>
          <p class="text-gray-600 hover:text-green-900 transition-colors duration-300">Grow your farming business with more visibility and reach.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Seasonal Tips Section -->
  <section id="tips" class="py-20 px-8 bg-white">
    <div class="max-w-6xl mx-auto text-center">
      <h2 class="text-4xl font-bold text-green-700 mb-12 hover:text-yellow-400 transition-colors duration-300">Seasonal Tips</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-green-50 p-8 rounded-2xl shadow-md hover:scale-105 hover:shadow-lg transition-transform duration-300" data-aos="fade-up">
          <h3 class="text-2xl font-bold text-green-700 mb-3 hover:text-yellow-400 transition-colors duration-300">Rabi Crops</h3>
          <p class="text-gray-600 hover:text-green-900 transition-colors duration-300">Wheat, barley, and mustard require proper soil preparation and timely sowing.</p>
        </div>
        <div class="bg-green-50 p-8 rounded-2xl shadow-md hover:scale-105 hover:shadow-lg transition-transform duration-300" data-aos="fade-up" data-aos-delay="100">
          <h3 class="text-2xl font-bold text-green-700 mb-3 hover:text-yellow-400 transition-colors duration-300">Kharif Crops</h3>
          <p class="text-gray-600 hover:text-green-900 transition-colors duration-300">Paddy, maize, and cotton depend heavily on rainfall and irrigation.</p>
        </div>
        <div class="bg-green-50 p-8 rounded-2xl shadow-md hover:scale-105 hover:shadow-lg transition-transform duration-300" data-aos="fade-up" data-aos-delay="200">
          <h3 class="text-2xl font-bold text-green-700 mb-3 hover:text-yellow-400 transition-colors duration-300">Horticulture</h3>
          <p class="text-gray-600 hover:text-green-900 transition-colors duration-300">Vegetables and fruits require pest control and drip irrigation for better yields.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer id="contact" class="bg-green-700 text-white py-10 px-8 text-center">
    <h3 class="text-3xl font-bold mb-4 hover:text-yellow-300 transition-colors duration-300">Contact Cropsetu</h3>
    <p class="mb-2 hover:text-yellow-200 transition-colors duration-300">Email: support@cropsetu.com</p>
    <p class="mb-2 hover:text-yellow-200 transition-colors duration-300">Phone: +91 98765 43210</p>
    <p>© 2026 Cropsetu. All rights reserved.</p>
  </footer>

  <!-- AOS JS -->
  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script>
    AOS.init({
      duration: 800,
      easing: 'ease-in-out',
      once: true,
      mirror: false
    });
  </script>

</body>
</html>
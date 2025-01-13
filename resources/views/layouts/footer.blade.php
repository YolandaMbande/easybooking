<!-- resources/views/layouts/footer.blade.php -->

<footer class="bg-gray-800 text-white py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Quick Links -->
            <div>
                <h3 class="font-bold text-lg mb-4">Quick Links</h3>
                <ul>
                    <li><a href="/" class="text-gray-400 hover:text-white">Home</a></li>
                    <li><a href="/explore" class="text-gray-400 hover:text-white">Explore Events</a></li>
                    <li><a href="/about" class="text-gray-400 hover:text-white">About Us</a></li>
                    <li><a href="{{ route('contact') }}" class="text-gray-400 hover:text-white">Contact Us</a></li>
                </ul>
            </div>

            <!-- Contact Information -->
            <div>
                <h3 class="font-bold text-lg mb-4">Contact Us</h3>
                <p class="text-gray-400">Email: support@easybooking.com</p>
                <p class="text-gray-400">Phone: +27 65 711 0159</p>
                <p class="text-gray-400">Address: 123 Event St, Gauteng, South Africa</p>
            </div>

            <!-- Social Media Icons -->
            <div class="flex space-x-4">
                <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook"></i></a>
                <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
                <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-linkedin"></i></a>
            </div>
        </div>
    </div>

    <div class="text-center text-gray-500 mt-8">
        &copy; 2024 Easy Booking. All rights reserved.
    </div>
</footer>

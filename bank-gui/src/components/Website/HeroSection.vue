<template>
   <!-- Hero Section -->
    <section id="home" class="section-padding pt-24 hero-gradient text-white relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 rounded-full bg-white/5"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 rounded-full bg-white/5"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 rounded-full bg-white/5"></div>
        </div>
        
        <div class="container mx-auto max-w-8xl px-4 relative z-10">
            <div class="lg:max-w-[86rem] mx-auto items-center justify-center flex flex-col md:flex-row  md:justify-between md:items-center md:space-y-0 md:space-x-10 md:py-10 w-full gap-6"> 
                <div class="items-center md:items-start">
                    <div class="md:w-full mb-10 md:mb-0">
                        <h1 class="text-4xl md:text-4xl lg:text-6xl font-bold mb-6 leading-tight text-center md:text-left">Build Custom Business Applications <span class="bg-gradient-to-r from-white to-blue-100 bg-clip-text text-transparent">in Minutes</span></h1>
                        <p class="text-xl md:text-2xl mb-8 opacity-90 max-w-3xl mx-auto">Transform your business processes with our powerful platform that generates custom CRUD applications tailored to your needs.</p>
                        <div class="flex flex-col sm:flex-row justify-center gap-4">
                            <a href="#packages" class="bg-white text-cyan-600 px-8 py-4 rounded-xl font-semibold hover:shadow-lg transition flex items-center justify-center gap-2">
                                <span>View Packages</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                            <a href="#about" class="glass-effect text-white px-8 py-4 rounded-xl font-semibold hover:bg-white/20 transition flex items-center justify-center gap-2">
                                <span>Learn More</span>
                                <i class="fas fa-play-circle"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- App Preview Slider -->
                <div class="mt-16 mx-auto md:mt-0 md:mx-0 md:w-1/2 relative">
                   <AppSlider />
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import AppSlider from '@/components/Website/AppSlider.vue';

// SEO Structured Data for Hero Section
const JsonLdProduct = () => {
  useJsonld({
    '@context': 'https://schema.org',
    '@type': 'SoftwareApplication',
    name: 'Banking Pro',
    applicationCategory: 'BusinessApplication',
    operatingSystem: 'Web',
    offers: {
      '@type': 'Offer',
      price: '49',
      priceCurrency: 'USD'
    },
    description: 'Build custom business applications in minutes with our powerful platform.',
    aggregateRating: {
      '@type': 'AggregateRating',
      ratingValue: '4.8',
      ratingCount: '150'
    }
  });
};
</script>
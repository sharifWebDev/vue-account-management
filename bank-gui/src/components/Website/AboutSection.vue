<template>
  <section id="about" class="section-padding bg-white dark:bg-gray-900">
    <div class="container mx-auto px-4">
      <div class="max-w-6xl mx-auto">
        <div class="text-center mb-16">
          <span class="text-gray-600 font-semibold text-sm uppercase tracking-wider mb-2 block">About Us</span>
          <h2 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-white mb-4">Why Choose Banking Pro?</h2>
          <p class="text-gray-600 dark:text-gray-300 max-w-2xl mx-auto text-lg">
            We empower businesses to build custom applications without the need for extensive development teams or technical expertise.
          </p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
          <div>
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Transforming Business Application Development</h3>
            <p class="text-gray-600 dark:text-gray-300 mb-6">
              Our mission is to simplify application development for businesses of all sizes by providing an intuitive platform that transforms complex requirements into functional applications with minimal effort.
            </p>
            
            <div class="space-y-6">
              <div v-for="feature in features" :key="feature.title" class="flex items-start">
                <div class="feature-icon p-3 rounded-xl mr-4 text-white shadow-md">
                  <i :class="feature.icon"></i>
                </div>
                <div>
                  <h4 class="font-semibold text-gray-800 dark:text-white mb-2">{{ feature.title }}</h4>
                  <p class="text-gray-600 dark:text-gray-300">{{ feature.description }}</p>
                </div>
              </div>
            </div>
          </div>
          
          <div class="relative">
            <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-2xl p-8 text-white shadow-xl floating-animation">
              <h3 class="text-2xl font-bold mb-6">Our Impact</h3>
              <div class="grid grid-cols-2 gap-6">
                <div v-for="stat in statistics" :key="stat.label" class="bg-white/10 rounded-xl p-4 text-center backdrop-blur-sm">
                  <div class="text-3xl font-bold mb-2">{{ stat.value }}</div>
                  <div class="text-white/80">{{ stat.label }}</div>
                </div>
              </div>
            </div>
            
            <!-- Floating elements -->
            <div class="absolute -top-4 -right-4 w-24 h-24 bg-gray-100 dark:bg-gray-900 rounded-full opacity-50"></div>
            <div class="absolute -bottom-4 -left-4 w-16 h-16 bg-gray-100 dark:bg-gray-900 rounded-full opacity-30"></div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
const features = [
  {
    icon: 'fas fa-rocket text-lg',
    title: 'Rapid Development',
    description: 'Build applications in days, not months, with our streamlined process and pre-built components.'
  },
  {
    icon: 'fas fa-shield-alt text-lg',
    title: 'Enterprise Security',
    description: 'All applications include built-in security features, authentication, and authorization following industry best practices.'
  },
  {
    icon: 'fas fa-cogs text-lg',
    title: 'Fully Customizable',
    description: 'Tailor every aspect of your application to match your business needs, branding, and workflows.'
  }
];

const statistics = [
  { value: '500+', label: 'Applications Built' },
  { value: '98%', label: 'Customer Satisfaction' },
  { value: '24/7', label: 'Support Available' },
  { value: '10+', label: 'Years Experience' }
];

// SEO Structured Data for About Section
const JsonLdAbout = () => {
  useJsonld({
    '@context': 'https://schema.org',
    '@type': 'AboutPage',
    name: 'About Banking Pro',
    description: 'Learn why businesses choose Banking Pro for rapid application development.',
    publisher: {
      '@type': 'Organization',
      name: 'Banking Pro'
    }
  });
};
</script>
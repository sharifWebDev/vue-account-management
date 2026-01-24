<template>
  <section id="packages"
    class="section-padding bg-gradient-to-b from-gray-50 to-white dark:from-gray-800 dark:to-gray-900">
    <div class="container mx-auto px-4">
      <div class="text-center mb-16">
        <span class="text-gray-600 font-semibold text-sm uppercase tracking-wider mb-2 block">Pricing Plans</span>
        <h2 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-white mb-4">Choose Your Package</h2>
        <p class="text-gray-600 dark:text-gray-300 max-w-2xl mx-auto text-lg">
          Select the package that best fits your business needs. All packages include our powerful application builder
          with different levels of features and support.
        </p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
        <!-- Basic Package -->
        <div
          class="package-card bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 border border-gray-100 dark:border-gray-700">
          <div class="text-center mb-8">
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Basic</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-4">Perfect for small projects</p>
            <div class="mt-4">
              <span class="text-4xl font-bold text-gray-800 dark:text-white">$49</span>
              <span class="text-gray-500 dark:text-gray-400">/month</span>
            </div>
          </div>

          <ul class="space-y-4 mb-8">
            <li v-for="feature in basicFeatures" :key="feature.text" class="flex items-start">
              <i :class="feature.iconClass" class="mt-1 mr-3"></i>
              <span :class="feature.textClass">{{ feature.text }}</span>
            </li>
          </ul>

          <div class="flex flex-col space-y-3">
            <button @click="viewPackageDetails('basic')"
              class="view-package-btn bg-cyan-600 text-white py-3 rounded-xl hover:bg-cyan-700 transition font-medium flex items-center justify-center gap-2">
              <span>View Details</span>
              <i class="fas fa-info-circle"></i>
            </button>
            <button @click="buyPackage('basic')"
              class="buy-package-btn border border-cyan-600 text-cyan-600 dark:text-cyan-400 py-3 rounded-xl hover:bg-cyan-50 dark:hover:bg-cyan-700 transition font-medium">
              Buy Now
            </button>
          </div>
        </div>

        <!-- Standard Package -->
        <div
          class="package-card bg-white dark:bg-cyan-800 rounded-2xl shadow-xl p-8 border-2 border-cyan-500 relative transform scale-105">
          <div
            class="absolute top-0 right-0 bg-cyan-500 text-white px-5 py-2 rounded-bl-2xl rounded-tr-xl text-sm font-semibold shadow-md">
            Most Popular</div>
          <div class="text-center mb-8">
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Standard</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-4">For growing businesses</p>
            <div class="mt-4">
              <span class="text-4xl font-bold text-gray-800 dark:text-white">$99</span>
              <span class="text-gray-500 dark:text-gray-400">/month</span>
            </div>
          </div>

          <ul class="space-y-4 mb-8">
            <li v-for="feature in standardFeatures" :key="feature.text" class="flex items-start">
              <i :class="feature.iconClass" class="mt-1 mr-3"></i>
              <span :class="feature.textClass">{{ feature.text }}</span>
            </li>
          </ul>

          <div class="flex flex-col space-y-3">
            <button @click="viewPackageDetails('standard')"
              class="view-package-btn bg-cyan-600 text-white py-3 rounded-xl hover:bg-cyan-700 transition font-medium flex items-center justify-center gap-2">
              <span>View Details</span>
              <i class="fas fa-info-circle"></i>
            </button>
            <button @click="buyPackage('standard')"
              class="buy-package-btn border border-cyan-600 text-cyan-600 dark:text-cyan-400 py-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition font-medium">
              Buy Now
            </button>
          </div>
        </div>
        
        <!-- Enterprise Package -->
        <div
          class="package-card bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 border border-gray-100 dark:border-gray-700">
          <div class="text-center mb-8">
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Enterprise</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-4">For large organizations</p>
            <div class="mt-4">
              <span class="text-4xl font-bold text-gray-800 dark:text-white">$199</span>
              <span class="text-gray-500 dark:text-gray-400">/month</span>
            </div>
          </div>

          <ul class="space-y-4 mb-8">
            <li v-for="feature in enterpriseFeatures" :key="feature.text" class="flex items-start">
              <i :class="feature.iconClass" class="mt-1 mr-3"></i>
              <span :class="feature.textClass">{{ feature.text }}</span>
            </li>
          </ul>

          <div class="flex flex-col space-y-3">
            <button @click="viewPackageDetails('enterprise')"
              class="view-package-btn bg-cyan-600 text-white py-3 rounded-xl hover:bg-cyan-700 transition font-medium flex items-center justify-center gap-2">
              <span>View Details</span>
              <i class="fas fa-info-circle"></i>
            </button>
            <button @click="buyPackage('enterprise')"
              class="buy-package-btn border border-cyan-600 text-cyan-600 dark:text-cyan-400 py-3 rounded-xl hover:bg-cyan-50 dark:hover:bg-cyan-700 transition font-medium">
              Buy Now
            </button>
          </div>
        </div>
      </div>

      <div class="mt-16 text-center">
        <p class="text-gray-500 dark:text-gray-400 mb-6">Need a custom solution for your business?</p>
        <a href="#contact"
          class="inline-flex items-center gap-2 bg-gray-800 dark:bg-gray-700 text-white px-6 py-3 rounded-xl hover:bg-gray-900 dark:hover:bg-gray-600 transition font-medium">
          <span>Contact Sales</span>
          <i class="fas fa-arrow-right"></i>
        </a>
      </div>
    </div>
  </section>
</template>

<script setup>
const basicFeatures = [
  { text: 'Laravel Service Pattern', iconClass: 'fas fa-check text-green-500', textClass: 'text-gray-600 dark:text-gray-300' },
  { text: 'Blade Templates', iconClass: 'fas fa-check text-green-500', textClass: 'text-gray-600 dark:text-gray-300' },
  { text: 'jQuery DataTable', iconClass: 'fas fa-check text-green-500', textClass: 'text-gray-600 dark:text-gray-300' },
  { text: 'API Integration', iconClass: 'fas fa-times text-red-400', textClass: 'text-gray-500 dark:text-gray-500' },
  { text: 'Advanced Features', iconClass: 'fas fa-times text-red-400', textClass: 'text-gray-500 dark:text-gray-500' }
];

const standardFeatures = [
  { text: 'Laravel Service Pattern', iconClass: 'fas fa-check text-green-500', textClass: 'text-gray-600 dark:text-gray-300' },
  { text: 'Interface Binding', iconClass: 'fas fa-check text-green-500', textClass: 'text-gray-600 dark:text-gray-300' },
  { text: 'API + Blade/jQuery', iconClass: 'fas fa-check text-green-500', textClass: 'text-gray-600 dark:text-gray-300' },
  { text: 'DataTable Integration', iconClass: 'fas fa-check text-green-500', textClass: 'text-gray-600 dark:text-gray-300' },
  { text: 'Advanced Features', iconClass: 'fas fa-times text-red-400', textClass: 'text-gray-500 dark:text-gray-500' }
];

const enterpriseFeatures = [
  { text: 'Laravel Service Repository', iconClass: 'fas fa-check text-green-500', textClass: 'text-gray-600 dark:text-gray-300' },
  { text: 'Interface Binding', iconClass: 'fas fa-check text-green-500', textClass: 'text-gray-600 dark:text-gray-300' },
  { text: 'API + Blade + jQuery', iconClass: 'fas fa-check text-green-500', textClass: 'text-gray-600 dark:text-gray-300' },
  { text: 'DataTable Integration', iconClass: 'fas fa-check text-green-500', textClass: 'text-gray-600 dark:text-gray-300' },
  { text: 'Advanced Features', iconClass: 'fas fa-check text-green-500', textClass: 'text-gray-600 dark:text-gray-300' }
];

const viewPackageDetails = (packageType) => {
  const event = new CustomEvent('show-package-modal', { detail: packageType });
  window.dispatchEvent(event);
};

const buyPackage = (packageType) => {
  const event = new CustomEvent('show-purchase-modal', { detail: packageType });
  window.dispatchEvent(event);
};

// SEO Structured Data for Products
const JsonLdProducts = () => {
  useJsonld([
    {
      '@context': 'https://schema.org',
      '@type': 'Product',
      name: 'Banking Pro Basic',
      description: 'Perfect for small projects with Laravel Service Pattern and Blade Templates',
      offers: {
        '@type': 'Offer',
        price: '49',
        priceCurrency: 'USD',
        priceValidUntil: '2024-12-31'
      }
    },
    {
      '@context': 'https://schema.org',
      '@type': 'Product',
      name: 'Banking Pro Standard',
      description: 'For growing businesses with API integration and advanced features',
      offers: {
        '@type': 'Offer',
        price: '99',
        priceCurrency: 'USD',
        priceValidUntil: '2024-12-31'
      }
    },
    {
      '@context': 'https://schema.org',
      '@type': 'Product',
      name: 'Banking Pro Enterprise',
      description: 'For large organizations with full feature set and dedicated support',
      offers: {
        '@type': 'Offer',
        price: '199',
        priceCurrency: 'USD',
        priceValidUntil: '2024-12-31'
      }
    }
  ]);
};
</script>
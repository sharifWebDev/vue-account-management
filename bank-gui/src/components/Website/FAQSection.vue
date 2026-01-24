<template>
  <section id="faq" class="section-padding bg-white dark:bg-gray-900">
    <div class="container mx-auto px-4">
      <div class="max-w-4xl mx-auto">
        <div class="text-center mb-16">
          <span class="text-gray-600 font-semibold text-sm uppercase tracking-wider mb-2 block">FAQ</span>
          <h2 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-white mb-4">Frequently Asked Questions</h2>
          <p class="text-gray-600 dark:text-gray-300 max-w-2xl mx-auto text-lg">
            Find answers to common questions about our platform and services.
          </p>
        </div>
        
        <div class="space-y-6">
          <div 
            v-for="(faq, index) in faqs" 
            :key="index" 
            class="bg-gray-50 dark:bg-gray-800 rounded-2xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg"
          >
            <button 
              @click="toggleFAQ(index)"
              class="faq-question w-full text-left p-6 font-semibold text-gray-800 dark:text-white flex justify-between items-center hover:bg-gray-100 dark:hover:bg-gray-700 transition"
              :aria-expanded="openFaqIndex === index"
              :aria-controls="`faq-answer-${index}`"
            >
              <span class="text-lg">{{ faq.question }}</span>
              <i 
                class="fas fa-chevron-down transition-transform text-gray-500"
                :class="{ 'rotate-180': openFaqIndex === index }"
              ></i>
            </button>
            <div 
              :id="`faq-answer-${index}`"
              class="faq-answer px-6 pb-6 text-gray-600 dark:text-gray-300"
              :class="{ 'hidden': openFaqIndex !== index }"
            >
              <p>{{ faq.answer }}</p>
            </div>
          </div>
        </div>
        
        <div class="text-center mt-12">
          <p class="text-cyan-600 dark:text-cyan-300 mb-6 text-lg">Still have questions?</p>
          <a 
            href="#contact" 
            class="inline-flex items-center gap-2 bg-gradient-to-r from-cyan-500 to-cyan-600 text-white px-8 py-3 rounded-xl hover:shadow-lg transition font-semibold"
          >
            <span>Contact Our Support Team</span>
            <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue';

const openFaqIndex = ref(null);

const faqs = [
  {
    question: 'How does the application builder work?',
    answer: 'Our application builder guides you through a simple step-by-step process to create custom CRUD applications. You start by defining your data structure, then our system generates the complete application based on your specifications, including database schema, backend API, and frontend interfaces. The generated code follows best practices and can be customized further if needed.'
  },
  {
    question: 'Can I export the applications I build?',
    answer: 'Yes, all applications created with our platform can be exported as complete Laravel projects. You\'ll receive all source code, database migrations, and documentation, allowing you to host the application on your own servers or modify it as needed. This ensures you maintain full ownership and control over your applications.'
  },
  {
    question: 'What payment methods do you accept?',
    answer: 'We accept all major credit cards (Visa, MasterCard, American Express), PayPal, and bank transfers for enterprise customers. All payments are processed securely through our PCI-compliant payment gateway. For annual subscriptions, we offer a 10% discount compared to monthly billing.'
  },
  {
    question: 'Is there a free trial available?',
    answer: 'We offer a 14-day free trial for all our packages. During the trial period, you have full access to all features of your selected package. No credit card is required to start your trial. At the end of the trial, you can choose to upgrade to a paid plan or your account will be automatically downgraded to the free tier with limited features.'
  },
  {
    question: 'What support is included with my package?',
    answer: 'All packages include email support with a 24-hour response time. Standard and Enterprise packages include live chat support during business hours. Enterprise customers also receive priority phone support and a dedicated account manager. Additionally, we provide comprehensive documentation, video tutorials, and a community forum for all users.'
  }
];

const toggleFAQ = (index) => {
  openFaqIndex.value = openFaqIndex.value === index ? null : index;
};

// SEO Structured Data for FAQ
const JsonLdFAQ = () => {
  useJsonld({
    '@context': 'https://schema.org',
    '@type': 'FAQPage',
    mainEntity: faqs.map(faq => ({
      '@type': 'Question',
      name: faq.question,
      acceptedAnswer: {
        '@type': 'Answer',
        text: faq.answer
      }
    }))
  });
};
</script>
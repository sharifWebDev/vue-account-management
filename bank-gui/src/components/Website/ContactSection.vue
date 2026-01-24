<template>
  <section id="contact" class="section-padding bg-gradient-to-b from-gray-50 to-white dark:from-gray-800 dark:to-gray-900">
    <div class="container mx-auto px-4">
      <div class="max-w-6xl mx-auto">
        <div class="text-center mb-16">
          <span class="text-cyan-600 font-semibold text-sm uppercase tracking-wider mb-2 block">Contact Us</span>
          <h2 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-white mb-4">Get In Touch</h2>
          <p class="text-gray-600 dark:text-gray-300 max-w-2xl mx-auto text-lg">
            Have questions? Get in touch with our team for more information about our platform and services.
          </p>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
          <div class="grid grid-cols-1 lg:grid-cols-2">
            <div class="p-8 lg:p-12">
              <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Send us a message</h3>
              
              <form @submit.prevent="handleSubmit" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
                    <input 
                      v-model="form.name"
                      type="text" 
                      id="name" 
                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 dark:bg-gray-700 dark:text-white transition" 
                      placeholder="Your name"
                      required
                    />
                  </div>
                  
                  <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Address</label>
                    <input 
                      v-model="form.email"
                      type="email" 
                      id="email" 
                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 dark:bg-gray-700 dark:text-white transition" 
                      placeholder="Your email"
                      required
                    />
                  </div>
                </div>
                
                <div>
                  <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Subject</label>
                  <input 
                    v-model="form.subject"
                    type="text" 
                    id="subject" 
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 dark:bg-gray-700 dark:text-white transition" 
                    placeholder="Subject of your message"
                    required
                  />
                </div>
                
                <div>
                  <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Message</label>
                  <textarea 
                    v-model="form.message"
                    id="message" 
                    rows="5" 
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 dark:bg-gray-700 dark:text-white transition" 
                    placeholder="Your message"
                    required
                  ></textarea>
                </div>
                
                <button 
                  type="submit" 
                  :disabled="isSubmitting"
                  class="w-full bg-gradient-to-r from-cyan-500 to-cyan-600 text-white py-3 rounded-xl hover:shadow-lg transition font-semibold flex items-center justify-center gap-2 disabled:opacity-50"
                >
                  <span>{{ isSubmitting ? 'Sending...' : 'Send Message' }}</span>
                  <i class="fas fa-paper-plane"></i>
                </button>
                
                <div v-if="submitMessage" class="mt-4 p-4 rounded-xl" :class="submitMessage.type === 'success' ? 'bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300' : 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300'">
                  {{ submitMessage.text }}
                </div>
              </form>
            </div>
            
            <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 text-white p-8 lg:p-12">
              <h3 class="text-2xl font-bold mb-8">Contact Information</h3>
              
              <div class="space-y-8">
                <div v-for="contact in contactInfo" :key="contact.title" class="flex items-start">
                  <div class="bg-white/10 p-3 rounded-xl mr-4 backdrop-blur-sm">
                    <i :class="contact.icon"></i>
                  </div>
                  <div>
                    <h4 class="font-semibold text-lg mb-2">{{ contact.title }}</h4>
                    <p class="text-white/80">{{ contact.details }}</p>
                  </div>
                </div>
              </div>
              
              <div class="mt-12 pt-8 border-t border-cyan-400">
                <h4 class="font-semibold text-lg mb-6">Follow Us</h4>
                <div class="flex space-x-4">
                  <a 
                    v-for="social in socialLinks" 
                    :key="social.name" 
                    :href="social.url" 
                    :aria-label="`Follow us on ${social.name}`"
                    class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center hover:bg-white/20 transition backdrop-blur-sm"
                    target="_blank"
                    rel="noopener noreferrer"
                  >
                    <i :class="social.icon"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue';

const form = ref({
  name: '',
  email: '',
  subject: '',
  message: ''
});

const isSubmitting = ref(false);
const submitMessage = ref(null);

const contactInfo = [
  {
    icon: 'fas fa-map-marker-alt',
    title: 'Our Office',
    details: '123 Business Avenue, Suite 100\nSan Francisco, CA 94107'
  },
  {
    icon: 'fas fa-phone',
    title: 'Phone',
    details: '+1 (555) 123-4567'
  },
  {
    icon: 'fas fa-envelope',
    title: 'Email',
    details: 'info@Bankingpro.com'
  },
  {
    icon: 'fas fa-clock',
    title: 'Business Hours',
    details: 'Monday - Friday: 9am - 6pm\nSaturday: 10am - 4pm'
  }
];

const socialLinks = [
  {
    name: 'Twitter',
    url: '#',
    icon: 'fab fa-twitter'
  },
  {
    name: 'Facebook',
    url: '#',
    icon: 'fab fa-facebook-f'
  },
  {
    name: 'LinkedIn',
    url: '#',
    icon: 'fab fa-linkedin-in'
  },
  {
    name: 'Instagram',
    url: '#',
    icon: 'fab fa-instagram'
  }
];

const handleSubmit = async () => {
  isSubmitting.value = true;
  submitMessage.value = null;
  
  try {
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 1500));
    
    submitMessage.value = {
      type: 'success',
      text: 'Thank you! Your message has been sent successfully. We\'ll get back to you within 24 hours.'
    };
    
    // Reset form
    form.value = {
      name: '',
      email: '',
      subject: '',
      message: ''
    };
  } catch (error) {
    submitMessage.value = {
      type: 'error',
      text: 'Something went wrong. Please try again or email us directly at info@Bankingpro.com'
    };
  } finally {
    isSubmitting.value = false;
  }
};

// SEO Structured Data for Contact
const JsonLdContact = () => {
  useJsonld({
    '@context': 'https://schema.org',
    '@type': 'ContactPage',
    name: 'Contact Banking Pro',
    description: 'Get in touch with our team for questions about our platform and services.',
    contactPoint: {
      '@type': 'ContactPoint',
      telephone: '+1-555-123-4567',
      contactType: 'customer service',
      email: 'info@Bankingpro.com',
      availableLanguage: 'English',
      hoursAvailable: {
        '@type': 'OpeningHoursSpecification',
        dayOfWeek: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
        opens: '09:00',
        closes: '18:00'
      }
    }
  });
};
</script>
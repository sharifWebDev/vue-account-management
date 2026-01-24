<template>
  <div class="slider-container bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-2xl">
    <div class="flex items-center justify-between mb-4">
      <div class="flex space-x-2">
        <div class="w-3 h-3 rounded-full bg-red-500"></div>
        <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
        <div class="w-3 h-3 rounded-full bg-green-500"></div>
      </div>
      <div class="text-sm font-medium">Banking Dashboard</div>
      <div class="flex space-x-2">
        <div class="w-2 h-2 rounded-full bg-white/50"></div>
        <div class="w-2 h-2 rounded-full bg-white/50"></div>
        <div class="w-2 h-2 rounded-full bg-white/50"></div>
      </div>
    </div>

    <div class="relative">
      <button @click="prevSlide" class="slider-nav prev">
        <i class="fas fa-chevron-left"></i>
      </button>

      <div class="slider-track" :style="{ transform: `translateX(-${currentSlide * 100}%)` }">
        <!-- Slide 1: User Management -->
        <div class="slider-slide" :class="{ 'slide-fade': currentSlide === 0 }">
          <div class="bg-gray-800 rounded-xl p-6 text-left shadow-inner">
            <div class="flex flex-col lg:flex-row gap-6">
              <div class="lg:w-1/3">
                <div class="bg-gray-700 rounded-xl p-4 mb-4">
                  <h3 class="font-semibold mb-3 text-white">CRUD Builder</h3>
                  <ul class="text-sm space-y-2">
                    <li class="flex items-center"><i class="fas fa-check text-green-400 mr-2"></i> <span
                        class="text-gray-300">Create Tables</span></li>
                    <li class="flex items-center"><i class="fas fa-check text-green-400 mr-2"></i> <span
                        class="text-gray-300">Define Fields</span></li>
                    <li class="flex items-center"><i class="fas fa-check text-green-400 mr-2"></i> <span
                        class="text-gray-300">Generate Code</span></li>
                    <li class="flex items-center"><i class="fas fa-check text-green-400 mr-2"></i> <span
                        class="text-gray-300">Export Application</span></li>
                  </ul>
                </div>
                <div class="bg-gray-700 rounded-xl p-4">
                  <h3 class="font-semibold mb-3 text-white">Quick Actions</h3>
                  <div class="flex flex-wrap gap-2">
                    <button class="bg-cyan-600 text-white text-xs px-3 py-1.5 rounded-lg">New Project</button>
                    <button class="bg-gray-600 text-white text-xs px-3 py-1.5 rounded-lg">Import DB</button>
                    <button class="bg-gray-600 text-white text-xs px-3 py-1.5 rounded-lg">Templates</button>
                  </div>
                </div>
              </div>
              <div class="lg:w-2/3">
                <div class="bg-gray-700 rounded-xl p-5">
                  <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-white">User Management</h3>
                    <button class="bg-cyan-600 text-white text-sm px-3 py-1.5 rounded-lg flex items-center gap-1">
                      <i class="fas fa-plus"></i>
                      <span>Add New</span>
                    </button>
                  </div>
                  <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                      <thead>
                        <tr class="border-b border-gray-600">
                          <th class="text-left py-3 text-gray-400 font-medium">Name</th>
                          <th class="text-left py-3 text-gray-400 font-medium">Email</th>
                          <th class="text-left py-3 text-gray-400 font-medium">Role</th>
                          <th class="text-left py-3 text-gray-400 font-medium">Status</th>
                          <th class="text-left py-3 text-gray-400 font-medium">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr class="border-b border-gray-700 hover:bg-gray-600/50 transition">
                          <td class="py-3 text-white">John Doe</td>
                          <td class="py-3 text-gray-300">john@example.com</td>
                          <td class="py-3"><span
                              class="bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded-full">Admin</span></td>
                          <td class="py-3"><span
                              class="bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded-full">Active</span></td>
                          <td class="py-3">
                            <button class="text-blue-400 mr-2 hover:text-blue-300 transition"><i
                                class="fas fa-edit"></i></button>
                            <button class="text-red-400 hover:text-red-300 transition"><i
                                class="fas fa-trash"></i></button>
                          </td>
                        </tr>
                        <tr class="hover:bg-gray-600/50 transition">
                          <td class="py-3 text-white">Jane Smith</td>
                          <td class="py-3 text-gray-300">jane@example.com</td>
                          <td class="py-3"><span
                              class="bg-purple-500/20 text-purple-400 text-xs px-2 py-1 rounded-full">User</span></td>
                          <td class="py-3"><span
                              class="bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded-full">Active</span></td>
                          <td class="py-3">
                            <button class="text-blue-400 mr-2 hover:text-blue-300 transition"><i
                                class="fas fa-edit"></i></button>
                            <button class="text-red-400 hover:text-red-300 transition"><i
                                class="fas fa-trash"></i></button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


        <!-- Slide 2: Analytics Dashboard -->
        <div class="slider-slide" :class="{ 'slide-fade': currentSlide === 1 }">
          <div class="bg-gray-800 rounded-xl p-6 text-left shadow-inner">
            <div class="flex flex-col lg:flex-row gap-6">
              <div class="lg:w-1/3">
                <div class="bg-gray-700 rounded-xl p-4 mb-4">
                  <h3 class="font-semibold mb-3 text-white">Analytics Overview</h3>
                  <ul class="text-sm space-y-2">
                    <li class="flex items-center"><i class="fas fa-chart-line text-blue-400 mr-2"></i> <span
                        class="text-gray-300">Real-time Metrics</span></li>
                    <li class="flex items-center"><i class="fas fa-users text-green-400 mr-2"></i> <span
                        class="text-gray-300">User Activity</span></li>
                    <li class="flex items-center"><i class="fas fa-database text-yellow-400 mr-2"></i> <span
                        class="text-gray-300">Data Insights</span></li>
                    <li class="flex items-center"><i class="fas fa-bell text-red-400 mr-2"></i> <span
                        class="text-gray-300">Notifications</span></li>
                  </ul>
                </div>
                <div class="bg-gray-700 rounded-xl p-4">
                  <h3 class="font-semibold mb-3 text-white">Quick Stats</h3>
                  <div class="space-y-3">
                    <div>
                      <div class="flex justify-between text-sm">
                        <span class="text-gray-300">Storage Used</span>
                        <span class="text-white">65%</span>
                      </div>
                      <div class="w-full bg-gray-600 rounded-full h-2 mt-1">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 65%"></div>
                      </div>
                    </div>
                    <div>
                      <div class="flex justify-between text-sm">
                        <span class="text-gray-300">Active Users</span>
                        <span class="text-white">1,247</span>
                      </div>
                      <div class="w-full bg-gray-600 rounded-full h-2 mt-1">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 80%"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="lg:w-2/3">
                <div class="bg-gray-700 rounded-xl p-5">
                  <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-white">Performance Metrics</h3>
                    <div class="flex space-x-2">
                      <button class="bg-gray-600 text-white text-xs px-3 py-1.5 rounded-lg">Daily</button>
                      <button class="bg-cyan-600 text-white text-xs px-3 py-1.5 rounded-lg">Weekly</button>
                      <button class="bg-gray-600 text-white text-xs px-3 py-1.5 rounded-lg">Monthly</button>
                    </div>
                  </div>
                  <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="bg-gray-800 rounded-lg p-4 text-center">
                      <div class="text-2xl font-bold text-white mb-1">1.2K</div>
                      <div class="text-xs text-gray-400">Page Views</div>
                    </div>
                    <div class="bg-gray-800 rounded-lg p-4 text-center">
                      <div class="text-2xl font-bold text-white mb-1">847</div>
                      <div class="text-xs text-gray-400">Unique Visitors</div>
                    </div>
                    <div class="bg-gray-800 rounded-lg p-4 text-center">
                      <div class="text-2xl font-bold text-white mb-1">64%</div>
                      <div class="text-xs text-gray-400">Conversion Rate</div>
                    </div>
                    <div class="bg-gray-800 rounded-lg p-4 text-center">
                      <div class="text-2xl font-bold text-white mb-1">2.3m</div>
                      <div class="text-xs text-gray-400">Avg. Session</div>
                    </div>
                  </div>
                  <div class="bg-gray-800 rounded-lg p-4">
                    <div class="flex justify-between items-center mb-3">
                      <h4 class="font-medium text-white">Traffic Sources</h4>
                      <span class="text-xs text-gray-400">Last 7 days</span>
                    </div>
                    <div class="space-y-2">
                      <div class="w-full bg-gray-700 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 42%"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


        <!-- Slide 3: Project Management -->
        <div class="slider-slide" :class="{ 'slide-fade': currentSlide === 2 }">
          <div class="bg-gray-800 rounded-xl p-6 text-left shadow-inner">
            <div class="flex flex-col lg:flex-row gap-6">
              <div class="lg:w-1/3">
                <div class="bg-gray-700 rounded-xl p-4 mb-4">
                  <h3 class="font-semibold mb-3 text-white">Project Tools</h3>
                  <ul class="text-sm space-y-2">
                    <li class="flex items-center"><i class="fas fa-tasks text-blue-400 mr-2"></i> <span
                        class="text-gray-300">Task Management</span></li>
                    <li class="flex items-center"><i class="fas fa-calendar-alt text-green-400 mr-2"></i> <span
                        class="text-gray-300">Project Timeline</span></li>
                    <li class="flex items-center"><i class="fas fa-file-alt text-yellow-400 mr-2"></i> <span
                        class="text-gray-300">Documentation</span></li>
                    <li class="flex items-center"><i class="fas fa-comments text-purple-400 mr-2"></i> <span
                        class="text-gray-300">Team Collaboration</span></li>
                  </ul>
                </div>
                <div class="bg-gray-700 rounded-xl p-4">
                  <h3 class="font-semibold mb-3 text-white">Recent Activity</h3>
                  <div class="space-y-3">
                    <div class="flex items-start">
                      <div class="w-2 h-2 bg-green-400 rounded-full mt-2 mr-2"></div>
                      <div class="text-xs">
                        <p class="text-white">New task assigned</p>
                        <p class="text-gray-400">2 hours ago</p>
                      </div>
                    </div>
                    <div class="flex items-start">
                      <div class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-2"></div>
                      <div class="text-xs">
                        <p class="text-white">Project updated</p>
                        <p class="text-gray-400">5 hours ago</p>
                      </div>
                    </div>
                    <div class="flex items-start">
                      <div class="w-2 h-2 bg-yellow-400 rounded-full mt-2 mr-2"></div>
                      <div class="text-xs">
                        <p class="text-white">Deadline approaching</p>
                        <p class="text-gray-400">1 day ago</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="lg:w-2/3">
                <div class="bg-gray-700 rounded-xl p-5">
                  <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-white">Project Dashboard</h3>
                    <button class="bg-cyan-600 text-white text-sm px-3 py-1.5 rounded-lg flex items-center gap-1">
                      <i class="fas fa-plus"></i>
                      <span>New Project</span>
                    </button>
                  </div>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="bg-gray-800 rounded-lg p-4">
                      <div class="flex justify-between items-start mb-2">
                        <h4 class="font-medium text-white">API Integration</h4>
                        <span class="bg-yellow-500/20 text-yellow-400 text-xs px-2 py-1 rounded-full">Planning</span>
                      </div>
                      <p class="text-xs text-gray-400 mb-3">Third-party service integration</p>
                      <div class="flex justify-between text-xs text-gray-400 mb-2">
                        <span>Progress</span>
                        <span>20%</span>
                      </div>
                      <div class="w-full bg-gray-600 rounded-full h-2">
                        <div class="bg-yellow-500 h-2 rounded-full" style="width: 20%"></div>
                      </div>
                    </div>
                    <div class="bg-gray-800 rounded-lg p-4">
                      <div class="flex justify-between items-start mb-2">
                        <h4 class="font-medium text-white">Database Migration</h4>
                        <span class="bg-purple-500/20 text-purple-400 text-xs px-2 py-1 rounded-full">Testing</span>
                      </div>
                      <p class="text-xs text-gray-400 mb-3">Legacy system migration</p>
                      <div class="flex justify-between text-xs text-gray-400 mb-2">
                        <span>Progress</span>
                        <span>85%</span>
                      </div>
                      <div class="w-full bg-gray-600 rounded-full h-2">
                        <div class="bg-purple-500 h-2 rounded-full" style="width: 85%"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <button @click="nextSlide" class="slider-nav next">
      <i class="fas fa-chevron-right"></i>
    </button>

    <!-- Slider Dots -->
    <div class="slider-dots">
      <div v-for="(dot, index) in 3" :key="index" class="slider-dot" :class="{ active: currentSlide === index }"
        @click="goToSlide(index)"></div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const currentSlide = ref(0);
const totalSlides = 3;
let autoSlideInterval;

const nextSlide = () => {
  currentSlide.value = (currentSlide.value + 1) % totalSlides;
  resetAutoSlide();
};

const prevSlide = () => {
  currentSlide.value = (currentSlide.value - 1 + totalSlides) % totalSlides;
  resetAutoSlide();
};

const goToSlide = (index) => {
  currentSlide.value = index;
  resetAutoSlide();
};

const startAutoSlide = () => {
  autoSlideInterval = setInterval(nextSlide, 5000);
};

const resetAutoSlide = () => {
  clearInterval(autoSlideInterval);
  startAutoSlide();
};

onMounted(() => {
  startAutoSlide();
});

onUnmounted(() => {
  clearInterval(autoSlideInterval);
});
</script>
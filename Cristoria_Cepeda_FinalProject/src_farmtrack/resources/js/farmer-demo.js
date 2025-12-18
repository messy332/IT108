// Farmer Demo Tutorial System
class FarmerDemo {
    constructor() {
        this.currentStep = 0;
        this.steps = [
            {
                title: "Welcome to FarmTrack!",
                description: "Let's take a quick tour to show you how to manage your farm effectively. This demo will guide you through the main features.",
                target: null,
                position: "center"
            },
            {
                title: "Step 1: Create Your Profile",
                description: "First, you'll need to create your farmer profile with your personal information, farm location, and contact details.",
                highlight: "#features",
                position: "bottom"
            },
            {
                title: "Step 2: Register Your Crops",
                description: "Add your crops to the system. Include details like crop type, planting date, expected harvest date, and farm area.",
                highlight: "#features",
                position: "bottom"
            },
            {
                title: "Step 3: Track Progress",
                description: "Regularly log your crop progress with photos and notes. Track growth stages from planting to harvest.",
                highlight: "#features",
                position: "bottom"
            },
            {
                title: "Step 4: Monitor Your Dashboard",
                description: "View all your crops, progress logs, and important notifications in one place. Get insights on your farm's performance.",
                highlight: "#features",
                position: "bottom"
            },
            {
                title: "Step 5: Harvest & Report",
                description: "When it's time to harvest, update your crop status and record the actual harvest data for future reference.",
                highlight: "#features",
                position: "bottom"
            },
            {
                title: "Ready to Get Started?",
                description: "That's it! You're now ready to start managing your farm with FarmTrack. Sign up to begin your journey.",
                target: null,
                position: "center"
            }
        ];
        this.overlay = null;
        this.modal = null;
    }

    start() {
        this.currentStep = 0;
        this.createOverlay();
        this.showStep();
    }

    createOverlay() {
        // Create dark overlay
        this.overlay = document.createElement('div');
        this.overlay.id = 'demo-overlay';
        this.overlay.className = 'fixed inset-0 bg-black bg-opacity-70 z-[9998] transition-opacity duration-300';
        document.body.appendChild(this.overlay);

        // Create modal container
        this.modal = document.createElement('div');
        this.modal.id = 'demo-modal';
        this.modal.className = 'fixed z-[9999] transition-all duration-300';
        document.body.appendChild(this.modal);

        // Prevent body scroll
        document.body.style.overflow = 'hidden';
    }

    showStep() {
        const step = this.steps[this.currentStep];
        
        // Clear highlight
        document.querySelectorAll('.demo-highlight').forEach(el => {
            el.classList.remove('demo-highlight');
        });

        // Add highlight if target exists
        if (step.highlight) {
            const target = document.querySelector(step.highlight);
            if (target) {
                target.classList.add('demo-highlight');
                target.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        // Position modal
        this.positionModal(step);

        // Update modal content
        this.modal.innerHTML = `
            <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 p-8 animate-slideUp">
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-semibold text-green-600">
                            Step ${this.currentStep + 1} of ${this.steps.length}
                        </span>
                        <button onclick="farmerDemo.close()" class="text-gray-400 hover:text-gray-600 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-3">${step.title}</h2>
                    <p class="text-gray-600 leading-relaxed">${step.description}</p>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex gap-2">
                        ${Array.from({ length: this.steps.length }, (_, i) => `
                            <div class="h-2 w-8 rounded-full ${i === this.currentStep ? 'bg-green-600' : 'bg-gray-300'}"></div>
                        `).join('')}
                    </div>
                    <div class="flex gap-3">
                        ${this.currentStep > 0 ? `
                            <button onclick="farmerDemo.previousStep()" 
                                class="px-4 py-2 text-gray-600 hover:text-gray-800 font-semibold transition">
                                Previous
                            </button>
                        ` : ''}
                        ${this.currentStep < this.steps.length - 1 ? `
                            <button onclick="farmerDemo.nextStep()" 
                                class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition shadow-md">
                                Next
                            </button>
                        ` : `
                            <button onclick="farmerDemo.finish()" 
                                class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition shadow-md">
                                Get Started
                            </button>
                        `}
                    </div>
                </div>
            </div>
        `;
    }

    positionModal(step) {
        if (step.position === 'center') {
            this.modal.className = 'fixed z-[9999] top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 transition-all duration-300';
        } else if (step.highlight) {
            const target = document.querySelector(step.highlight);
            if (target) {
                const rect = target.getBoundingClientRect();
                if (step.position === 'bottom') {
                    this.modal.className = 'fixed z-[9999] transition-all duration-300';
                    this.modal.style.top = `${rect.bottom + 20}px`;
                    this.modal.style.left = '50%';
                    this.modal.style.transform = 'translateX(-50%)';
                }
            }
        }
    }

    nextStep() {
        if (this.currentStep < this.steps.length - 1) {
            this.currentStep++;
            this.showStep();
        }
    }

    previousStep() {
        if (this.currentStep > 0) {
            this.currentStep--;
            this.showStep();
        }
    }

    finish() {
        this.close();
        // Open register modal
        if (typeof openModal === 'function') {
            openModal('registerModal');
        }
    }

    close() {
        if (this.overlay) {
            this.overlay.remove();
        }
        if (this.modal) {
            this.modal.remove();
        }
        document.querySelectorAll('.demo-highlight').forEach(el => {
            el.classList.remove('demo-highlight');
        });
        document.body.style.overflow = '';
    }
}

// Initialize demo instance
const farmerDemo = new FarmerDemo();

// Make it globally accessible
window.farmerDemo = farmerDemo;

<script setup lang="ts">


const { user } = useAuth()

const profile = defineModel<any>('profile')

const fileInput = ref<HTMLInputElement | null>(null)

console.log("USER: ", user.value);

console.log("PROFILE: ", profile.value);


const readyForWorkOptions = [
    'Immediate',
    '1 Week',
    'Maybe in 2 Weeks',
    'Maybe in 1 Month',
    'Maybe in 3 Months',
    'Currently Passive'
];

const triggerFileInput = () => {
    fileInput.value?.click()
}

const handleFileChange = (event: Event) => {
    const file = (event.target as HTMLInputElement).files[0]
    if (file) {
        profile.value.avatar = file
    }
}

const numberOfDescChars = computed(() => {
    if (profile.value?.bio) {
        return profile.value.bio.length
    }
    return 0
})

</script>
<template>
    <div>
        <header class="mb-12 text-center">
            <h1 class="text-5xl font-extrabold font-headline tracking-tighter text-on-surface mb-4">The Digital
                Architect
            </h1>
            <p class="text-lg text-on-surface-variant max-w-2xl mx-auto leading-relaxed">Let's build your
                professional
                presence. This information will be used to match you with top-tier engineering teams.</p>
        </header>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
            <!-- Left: Profile Photo Card -->
            <div class="md:col-span-4 flex flex-col items-center">
                <div
                    class="w-full aspect-square rounded-3xl bg-surface-container-lowest shadow-[0_32px_64px_-4px_rgba(1,0,110,0.06)] p-8 flex flex-col items-center justify-center text-center group cursor-pointer border-2 border-dashed border-outline-variant/30 hover:border-primary/50 transition-all">
                    <div class="w-24 h-24 rounded-full bg-primary-fixed flex items-center justify-center mb-6 group-hover:scale-110 transition-transform"
                        @click="triggerFileInput">
                        <input type="file" ref="fileInput" @change="handleFileChange" style="display: none" />
                        <span class="material-symbols-outlined text-primary text-4xl"
                            data-icon="add_a_photo">add_a_photo</span>

                    </div>
                    <h3 class="font-headline font-bold text-on-surface mb-2">Upload Photo</h3>
                    <!-- <p class="text-xs text-on-surface-variant px-4" v-if="profile.avatar">{{ profile.avatar.name }}</p>
                    <p class="text-xs text-on-surface-variant px-4" v-else>Drag and drop or click to browse. JPG, PNG
                        (Max 5MB)</p> -->
                </div>
            </div>
            <!-- Right: Main Fields -->
            <div class="md:col-span-8 space-y-8">
                <div
                    class="bg-surface-container-lowest rounded-2xl p-8 md:p-10 shadow-[0_32px_64px_-4px_rgba(1,0,110,0.06)]">
                    <div class="space-y-10">
                        <!-- Name & Headline -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="relative group">
                                <label class="block text-xs font-bold text-primary uppercase tracking-widest mb-2">Full
                                    Name</label>
                                <!-- <input
                                    class="w-full bg-transparent border-0 border-b-2 border-outline-variant/30 focus:ring-0 focus:border-primary py-3 px-0 text-on-surface font-medium placeholder:text-outline-variant transition-all outline-none"
                                     type="text" :value="user.name" readonly /> -->
                            </div>
                            <div class="relative group">
                                <label
                                    class="block text-xs font-bold text-primary uppercase tracking-widest mb-2">Email</label>
                                <!-- <input
                                    class="w-full bg-transparent border-0 border-b-2 border-outline-variant/30 focus:ring-0 focus:border-primary py-3 px-0 text-on-surface font-medium placeholder:text-outline-variant transition-all outline-none"
                                    type="email" :value="user.email" readonly /> -->
                            </div>
                        </div>
                        <!-- Name & Headline -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="relative group">
                                <label
                                    class="block text-xs font-bold text-primary uppercase tracking-widest mb-2">Phone</label>
                                <input
                                    class="w-full bg-transparent border-0 border-b-2 border-outline-variant/30 focus:ring-0 focus:border-primary py-3 px-0 text-on-surface font-medium placeholder:text-outline-variant transition-all"
                                    placeholder="e.g. Alex Rivera" type="text" v-model="profile.phone" />
                            </div>
                            <div class="relative group">
                                <label class="block text-xs font-bold text-primary uppercase tracking-widest mb-2">Job
                                    Title</label>
                                <input
                                    class="w-full bg-transparent border-0 border-b-2 border-outline-variant/30 focus:ring-0 focus:border-primary py-3 px-0 text-on-surface font-medium placeholder:text-outline-variant transition-all"
                                    placeholder="e.g. Software Engineer" type="text" v-model="profile.job_title" />
                            </div>
                        </div>
                        <!-- Location & Availability -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="relative group">
                                <label
                                    class="block text-xs font-bold text-primary uppercase tracking-widest mb-2">Location</label>
                                <div
                                    class="flex items-center space-x-2 border-b-2 border-outline-variant/30 focus-within:border-primary transition-all">
                                    <span
                                        class="material-symbols-outlined text-outline-variant group-focus-within:text-primary"
                                        data-icon="location_on">location_on</span>
                                    <input
                                        class="w-full bg-transparent border-0 focus:ring-0 py-3 px-0 text-on-surface font-medium placeholder:text-outline-variant"
                                        placeholder="City, Country" type="text" v-model="profile.location" />
                                </div>
                            </div>
                            <div class="relative group">
                                <label
                                    class="block text-xs font-bold text-primary uppercase tracking-widest mb-2">Availability</label>
                                <div class="relative">
                                    <select
                                        class="w-full bg-transparent border-0 border-b-2 border-outline-variant/30 focus:ring-0 focus:border-primary py-3 px-0 text-on-surface font-medium appearance-none transition-all"
                                        v-model="profile.ready_for_work">
                                        <option v-for="option in readyForWorkOptions" :key="option" :value="option">
                                            {{ option }}
                                        </option>
                                    </select>
                                    <span
                                        class="absolute right-0 top-3 material-symbols-outlined text-outline-variant pointer-events-none"
                                        data-icon="expand_more">expand_more</span>
                                </div>
                            </div>
                        </div>
                        <!-- Bio -->
                        <div class="relative group">
                            <label class="block text-xs font-bold text-primary uppercase tracking-widest mb-2">Short
                                Bio</label>
                            <textarea
                                class="w-full bg-surface-container-low/30 border-0 border-b-2 border-outline-variant/30 focus:ring-0 focus:border-primary p-4 rounded-t-lg text-on-surface font-medium placeholder:text-outline-variant transition-all"
                                placeholder="Tell us about your architectural philosophy and tech stacks..." rows="4"
                                v-model="profile.bio"></textarea>
                            <div class="flex justify-end mt-2">
                                <span class="text-[10px]  font-bold uppercase tracking-widest"
                                    :class="numberOfDescChars >= 500 ? 'text-red-500' : 'text-on-surface-variant'">{{
                                    numberOfDescChars }}
                                    / 500
                                    characters</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
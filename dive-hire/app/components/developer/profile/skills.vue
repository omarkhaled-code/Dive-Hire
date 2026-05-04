<script setup lang="ts">

const profile = defineModel<any>('profile')
const { mode } = defineProps(['mode'])

const newSkill = ref('')

const handleAddNewSkill = (skill) => {
    if (!skill) {
        return;
    }
    if (mode && mode === 'update') {

        profile.value.skills.push({ name: skill })
    } else {
        profile.value.skills.push(skill)
    }

    newSkill.value = ''
}


const removeSkill = (skill) => {
    if (mode && mode === 'update') {
        profile.value.skills = profile.value.skills.filter(s => s.name !== skill)
    } else {

        profile.value.skills = profile.value.skills.filter(s => s !== skill)
    }
}


const suggestedSkills = computed(() => {
    const allSuggested = ['Next.js', 'Docker', 'Redis', 'AWS Lambda', 'Tailwind CSS', 'Kubernetes', 'GraphQL', 'TypeScript', 'React', 'Node.js']
    if (newSkill.value.trim() !== '') {
        return allSuggested.filter(skill => skill.toLowerCase().includes(newSkill.value.trim().toLowerCase()) && !profile.value.skills.includes(skill))
    }

    if (mode && mode === 'update') {
        let skillsArr: any = []
        profile.value?.skills.map((s) => {
            skillsArr.push(s.name)
        })
        return allSuggested.filter(skill => !skillsArr.includes(skill))
    }

    return allSuggested.filter(skill => !profile.value.skills.includes(skill))
})


const fileInput = ref<HTMLInputElement | null>(null)


const triggerFileInput = () => {
    fileInput.value?.click()
}

const handleFileChange = (event: Event) => {
    const file = (event.target as HTMLInputElement).files[0]
    if (file) {
        profile.value.cv = file
    }
}
</script>

<template>
    <div>
        <header class="mb-12 text-center">
            <h1 class="text-5xl font-extrabold font-headline tracking-tighter text-on-surface mb-4">Skills & Mastery
            </h1>
            <p class="text-lg text-on-surface-variant max-w-2xl mx-auto leading-relaxed">Map your technical ecosystem
                and upload your credentials. Our AI architect engine will analyze your stack depth.</p>
        </header>
        <div class="lg:col-span-8 space-y-8">
            <section class="bg-surface-container-lowest p-8 rounded-2xl shadow-[0_32px_64px_-4px_rgba(1,0,110,0.06)]">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold font-headline tracking-tight text-on-surface">Technical Stack
                        Mapping</h3>
                    <span
                        class="text-xs font-bold text-on-surface-variant bg-surface-container px-3 py-1 rounded-full">12
                        Tags Added</span>
                </div>
                <!-- Interactive Search Bar -->
                <div class="relative mb-8">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline"
                        data-icon="search">search</span>
                    <input
                        class="w-full pl-12 pr-4 py-4 bg-surface-container-low border-none rounded-xl focus:ring-2 focus:ring-primary/20 text-on-surface placeholder:text-outline transition-all"
                        placeholder="Search technology (e.g. React, Kubernetes, Rust...)" type="text"
                        v-model="newSkill" />
                </div>

                <div class="flex flex-wrap gap-3 mb-10">
                    <div class="flex items-center gap-2 bg-primary-fixed text-on-primary-fixed px-4 py-2 rounded-lg font-bold text-sm"
                        v-for="skill in profile?.skills" :key="skill" v-if="!mode || mode === 'create'">
                        <span>{{ skill }}</span>
                        <button class="hover:text-primary" @click="removeSkill(skill)">
                            <span class="material-symbols-outlined text-sm cursor-pointer"
                                data-icon="close">close</span>
                        </button>
                    </div>
                    <div class="flex items-center gap-2 bg-primary-fixed text-on-primary-fixed px-4 py-2 rounded-lg font-bold text-sm"
                        v-for="skill in profile?.skills" :key="skill" v-if="mode && mode === 'update'">
                        <span>{{ skill.name }}</span>
                        <button class="hover:text-primary" @click="removeSkill(skill.name)">
                            <span class="material-symbols-outlined text-sm cursor-pointer"
                                data-icon="close">close</span>
                        </button>
                    </div>

                </div>
                <!-- Suggested Skills Section -->
                <div>
                    <p class="text-[11px] font-black text-on-surface-variant uppercase tracking-widest mb-4">
                        Suggested for your profile</p>
                    <div class="flex flex-wrap gap-2">
                        <button
                            class="ghost-border px-4 py-2 rounded-lg text-xs font-bold text-on-surface-variant hover:bg-primary-fixed hover:text-primary hover:border-primary transition-all flex items-center gap-2 cursor-pointer"
                            v-for="suggestion in suggestedSkills" :key="suggestion"
                            @click="handleAddNewSkill(suggestion)">
                            <span class="material-symbols-outlined text-xs" data-icon="add">add</span> {{ suggestion }}
                        </button>
                        <button
                            class="ghost-border px-4 py-2 rounded-lg text-xs font-bold text-on-surface-variant hover:bg-primary-fixed hover:text-primary hover:border-primary transition-all flex items-center gap-2 cursor-pointer"
                            v-if="suggestedSkills.length === 0 && newSkill !== ''" @click="handleAddNewSkill(newSkill)">
                            <span class="material-symbols-outlined text-xs" data-icon="add">add</span> {{ newSkill }}
                        </button>

                        <!-- <button
                            class="ghost-border px-4 py-2 rounded-lg text-xs font-bold text-on-surface-variant hover:bg-primary-fixed hover:text-primary hover:border-primary transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined text-xs" data-icon="add">add</span> Docker
                        </button>
                        <button
                            class="ghost-border px-4 py-2 rounded-lg text-xs font-bold text-on-surface-variant hover:bg-primary-fixed hover:text-primary hover:border-primary transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined text-xs" data-icon="add">add</span> Redis
                        </button>
                        <button
                            class="ghost-border px-4 py-2 rounded-lg text-xs font-bold text-on-surface-variant hover:bg-primary-fixed hover:text-primary hover:border-primary transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined text-xs" data-icon="add">add</span> AWS
                            Lambda
                        </button>
                        <button
                            class="ghost-border px-4 py-2 rounded-lg text-xs font-bold text-on-surface-variant hover:bg-primary-fixed hover:text-primary hover:border-primary transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined text-xs" data-icon="add">add</span> Tailwind
                            CSS
                        </button> -->
                    </div>
                </div>
            </section>
            <!-- Section: Resume Upload (Asymmetric Wide Cell) -->
            <section
                class="bg-surface-container-low p-8 rounded-2xl border-2 border-dashed border-outline-variant/30 flex flex-col items-center justify-center text-center py-16">
                <div
                    class="w-16 h-16 architect-gradient rounded-full flex items-center justify-center text-white mb-6 shadow-xl">
                    <span class="material-symbols-outlined text-3xl" data-icon="upload_file">upload_file</span>
                </div>
                <h3 class="text-2xl font-bold font-headline text-on-surface mb-2">Upload your CV / Resume</h3>
                <p class="text-on-surface-variant mb-8 max-w-md">Drag and drop your file here, or browse. We
                    support PDF, DOCX and Markdown. AI parsing will auto-suggest missing skills.</p>
                <div class="flex flex-col gap-4">
                    <input type="file" ref="fileInput" @change="handleFileChange" style="display: none" />

                    <button
                        class="px-8 py-3 bg-white text-primary font-bold rounded-lg shadow-sm hover:shadow-md transition-all uppercase tracking-widest text-xs cursor-pointer"
                        @click="triggerFileInput">Browse
                        Files
                    </button>
                    <!-- Show selected file name -->
                    <p v-if="profile?.cv">Selected: {{ profile?.cv.name }}</p>
                </div>
            </section>

        </div>
    </div>
</template>

<style scoped>
.tonal-shift {
    transition: background-color 0.3s ease;
}

.architect-gradient {
    background: linear-gradient(135deg, #0300b2 0%, #1313ec 100%);
}

.ghost-border {
    border: 1px solid rgba(198, 196, 218, 0.2);
}
</style>
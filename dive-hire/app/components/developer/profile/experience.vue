<script setup lang="ts">

const {user} = useAuth()

const profile = defineModel< any>('profile')




console.log("USER: ", user.value);

console.log("PROFILE: ", profile.value);

const handleAddExperience = () => {
    if (profile.value.experiences.length >= 5) {
        alert('Maximum of 5 experiencesentries allowed.')
        return
    }

    // Prohibition of additional entry if the last entry is incomplete
    const lastEntry: Experience | any = profile.value.experiences[0]
    if (!lastEntry.company_name || !lastEntry.job_title || !lastEntry.start_date || !lastEntry.end_date) {
        alert('Please complete the current experiencesentry before adding a new one.')
        return
    }

    const newEntry = {
        id: profile.value.experiences.length + 1,
        company_name: '',
        job_title: '',
        start_date: '',
        end_date: '',
        achievements: [{ achievement: "" }]

    }
    profile.value.experiences = [newEntry, ...profile.value.experiences]
}

const handleRemoveEntery = (entryId: number) => {
    profile.value.experiences = profile.value.experiences.filter(
        entry => entry.id !== entryId
    )
}

const addKey = (id: number) => {
  profile.value.experiences = profile.value.experiences.map(exp => {
    if (exp.id === id) {
      return {
        ...exp,
        achievements: [
          ...(exp.achievements || []),
          { achievement: "" }
        ]
      }
    }
    return exp
  })
}

const removeKey = (expId: number, index: number) => {
    const exp = profile.value.experiences.find(e => e.id === expId)
    if (exp) {
        exp.achievements = exp.achievements.filter((_, i) => i !== index)
    }
}
</script>

<template>

    <div>
        <header class="mb-12 text-center">
            <h1 class="text-5xl font-extrabold font-headline tracking-tighter text-on-surface mb-4">Professional History
            </h1>
            <p class="text-lg text-on-surface-variant max-w-2xl mx-auto leading-relaxed">Tell the community about your
                journey. Your experienceshelps us match you with high-impact projects.</p>
        </header>
        <!-- Timeline Section -->
        <div class="relative space-y-16">
            <!-- Vertical Timeline Line -->
            <div class="absolute left-8 top-4 bottom-4 w-px bg-outline-variant/30"></div>
            <!-- Role Card  -->
            <div class="relative  group" v-for="(role, index) in profile.experiences" :key="role.id">
                <!-- Timeline Marker -->
                <div class="absolute left-[29px] top-1 w-4 h-4 rounded-full  ring-8 ring-surface shadow-sm z-10"
                    :class="index === 0 ? 'bg-[#1313ec]' : 'bg-gray-400'">
                </div>
                <div
                    class="bg-surface-container-lowest rounded-2xl p-8 shadow-[0_32px_64px_-4px_rgba(1,0,110,0.06)] border border-transparent hover:border-primary/10 transition-all duration-300">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div class="space-y-2">
                            <label
                                class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Company
                                Name</label>
                            <input
                                class="w-full bg-surface-container-lowest border-0 border-b-2 border-outline-variant/30 focus:ring-0 focus:border-[#1313ec] transition-all py-2 px-0 text-lg font-medium "
                                type="text" placeholder="Company Name..." v-model="role.company_name" />
                        </div>
                        <div class="space-y-2">
                            <label
                                class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Job
                                Title</label>
                            <input
                                class="w-full bg-surface-container-lowest border-0 border-b-2 border-outline-variant/30 focus:ring-0 focus:border-[#1313ec] transition-all py-2 px-0 text-lg font-medium"
                                type="text" placeholder="Job Title..." v-model="role.job_title" />
                        </div>
                        <div class="space-y-2">
                            <label
                                class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Start
                                Date</label>
                            <input
                                class="w-full bg-surface-container-lowest border-0 border-b-2 border-outline-variant/30 focus:ring-0 focus:border-[#1313ec] transition-all py-2 px-0"
                                type="month" v-model="role.start_date" />
                        </div>
                        <div class="space-y-2">
                            <label
                                class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">End
                                Date</label>
                            <input
                                class="w-full bg-surface-container-lowest border-0 border-b-2 border-outline-variant/30 focus:ring-0 focus:border-[#1313ec] transition-all py-2 px-0"
                                type="month" v-model="role.end_date" />
                        </div>
                    </div>

                    <div class="space-y-6">


                        <!-- <textarea
                            class="w-full bg-surface-container-low rounded-xl border-none focus:ring-2 focus:ring-[#1313ec]/20 transition-all p-4 text-on-surface-variant"
                            placeholder="E.g. Scaled infrastructure to support 1M+ MAU, reduced latency by 40%..."
                            placeholder:text-on-surface-variant rows="3" v-model="role.achievements[0].achievements">{{ role.achievements[0].achievement }}</textarea>
                            {{ role.achievements[0].achievement }} -->
                        <div class="flex flex-col gap-6">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-slate-900 dark:text-slate-100 text-sm font-semibold">Key
                                        Achievements &amp; Impact</h3>
                                </div> 
                                <button
                                    class="text-[#1313ec] hover:text-[#1313ec]/80 text-sm font-medium flex items-center gap-1 transition-colors cursor-pointer"
                                    type="button" @click="addKey(role.id)">
                                    <span class="material-symbols-outlined text-[18px]">add</span> Add Item
                                </button>
                            </div>
                            <ul class="flex flex-col gap-6">
                                <li class="flex items-start gap-3 group" v-for="(key, index) in role.achievements || []"
                                    :key="index">
                                    <div class="mt-2 text-slate-400">
                                        <span class="material-symbols-outlined text-[1rem] text-blue-800">
                                            task_alt
                                        </span>
                                    </div>
                                    <input
                                        class="flex-1 rounded-lg border text-slate-900 dark:text-slate-100 text-sm px-3 py-2 transition-all"
                                        :class="!key.achievement ? 'empty-input' : 'text-input'" type="text"
                                        v-model="role.achievements[index].achievement" />
                                    <button
                                        class="mt-2 text-slate-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-opacity focus:opacity-100 cursor-pointer"
                                        type="button" @click="removeKey(role.id, index)">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </li>
                                <!-- <p class="text-red-500" v-if="errors.responsibilities">{{ errors?.responsibilities }}</p> -->

                            </ul>
                        </div>
                    </div>



                    <div class="mt-10 flex justify-end">
                        <button
                            class="text-tertiary text-xs font-bold uppercase tracking-wider flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer"
                            @click="handleRemoveEntery(role.id)">
                            <span class="material-symbols-outlined text-sm" data-icon="delete">delete</span>
                            <span>Remove Entry</span>
                        </button>
                    </div>
                </div>
            </div>

            <button
                class="text-primary text-xs font-bold uppercase tracking-wider flex items-center space-x-1 hover:text-primary-container transition-colors cursor-pointer"
                @click="handleAddExperience">
                <span class="material-symbols-outlined text-sm" data-icon="add">add</span>
                <span>Add Experience</span>
            </button>
        </div>
    </div>
</template>

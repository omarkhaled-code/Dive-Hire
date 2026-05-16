<script setup lang="ts">

interface Achievement {
    achievement: string
}

interface Experience {
    id: number
    company_name: string
    job_title: string
    start_date: string
    end_date: string
    achievements: Achievement[]
}

interface Project {
    id: number
    title: string
    description: string
    techStack: string[]
    projectLink: string
    thumbnail: File | null
    isOpen: boolean
}

interface Profile {
    avatar: File | string
    phone: string
    location: string
    job_title: string
    ready_for_work: string
    bio: string
    skills: string[]
    experiences: Experience[]
    education: any[]
    experience_years: number
    cv: File | string
    projects: Project[]
}

type Page = 'basic-info' | 'experience' | 'skills' | 'projects'

const displayPage = ref<Page>('basic-info')
const displayErrorToast = ref<boolean>(false)

const errorMessage = ref('')

const profile = ref<Profile>({
    avatar: '',
    phone: '0123456789',
    location: 'Cairo, Egypt',
    job_title: 'Senior Full-Stack Architect',
    ready_for_work: 'Immediate',
    bio: 'Experienced software architect with 10+ years in designing scalable web applications. Proficient in modern frameworks and cloud technologies. Passionate about building high-performance solutions and leading engineering teams to success.',
    skills: [
        'JavaScript',
        'TypeScript',
        'Vue.js',
        'Node.js',
        'AWS',
    ],
    experiences: [
        {
            id: 1,
            company_name: 'Tech Innovators Inc.',
            job_title: 'Senior Software Architect',
            start_date: '2026-04',
            end_date: '2026-05',
            achievements: [
                {
                    achievement: 'Led the architecture and development of a high-traffic e-commerce platform, resulting in a 30% increase in sales.'
                },
                {
                    achievement: 'Implemented microservices architecture, improving system scalability and reducing downtime by 40%.'
                },
                {
                    achievement: 'Mentored a team of 10+ developers, fostering a culture of continuous learning and improvement.'
                }
            ]
        }
    ],
    education: [],
    experience_years: 4,
    cv: '',
    projects: [
        {
            id: 1,
            title: 'Real Time Analytics Dashboard',
            description: 'Briefly describe the problem you solved and your contribution...',
            techStack: ['Nuxt.js', 'Vue.js', 'Laravel', 'Tailwind'],
            projectLink: 'https://project-link.com',
            thumbnail: null,
            isOpen: true
        },
    ]
})

const checkExperienceCompletion = () => {
    if (!profile.value.experiences.length) {
        return true
    }

    return profile.value.experiences.every((exp) => {
        return (
            exp.company_name?.trim() &&
            exp.job_title?.trim() &&
            exp.start_date &&
            exp.end_date
        )
    })
}

const handleContinue = () => {
    if (displayPage.value === 'basic-info') {
        displayPage.value = 'experience'

    } else if (displayPage.value === 'experience') {

        if (!checkExperienceCompletion()) {
            alert('Please complete your experience entry before proceeding.')
            return
        }

        displayPage.value = 'skills'

    } else if (displayPage.value === 'skills') {

        displayPage.value = 'projects'

    } else {

        handleCreateProfile()

    }
}

const handleBack = () => {
    if (displayPage.value === 'experience') {
        displayPage.value = 'basic-info'

    } else if (displayPage.value === 'skills') {

        displayPage.value = 'experience'

    } else if (displayPage.value === 'projects') {

        displayPage.value = 'skills'

    }
}

const continueTo = computed(() => {

    switch (displayPage.value) {

        case 'basic-info':
            return 'Continue To Experience'

        case 'experience':
            return 'Continue To Skills'

        case 'skills':
            return 'Continue To Projects'

        default:
            return 'Save Profile'
    }

})

const goTo = (route: Page = 'basic-info') => {

    if (
        displayPage.value === 'experience' &&
        !checkExperienceCompletion()
    ) {
        alert('Complete experience first')
        return
    }

    displayPage.value = route
}

const handleCreateProfile = async () => {

    try {

        const formData = new FormData()

        formData.append('phone', profile.value.phone)
        formData.append('location', profile.value.location)
        formData.append('job_title', profile.value.job_title)
        formData.append('bio', profile.value.bio)
        formData.append('ready_for_work', profile.value.ready_for_work)

        // Skills
        profile.value.skills.forEach((skill, index) => {
            formData.append(`skills[${index}]`, skill)
        })

        // Experience
        profile.value.experiences.forEach((exp, index) => {

            formData.append(`experience[${index}][company_name]`, exp.company_name)
            formData.append(`experience[${index}][job_title]`, exp.job_title)
            formData.append(`experience[${index}][start_date]`, exp.start_date)
            formData.append(`experience[${index}][end_date]`, exp.end_date)

            exp.achievements.forEach((ach, achIndex) => {

                formData.append(
                    `experience[${index}][achievements][${achIndex}]`,
                    ach.achievement
                )

            })

        })

        // Projects
        profile.value.projects.forEach((pro, index) => {

            formData.append(`project[${index}][title]`, pro.title)
            formData.append(`project[${index}][description]`, pro.description)
            formData.append(`project[${index}][projectLink]`, pro.projectLink)

            pro.techStack.forEach((tag, tagIndex) => {

                formData.append(
                    `project[${index}][techStack][${tagIndex}]`,
                    tag
                )

            })

            if (pro.thumbnail instanceof File) {

                formData.append(
                    `project[${index}][thumbnail]`,
                    pro.thumbnail
                )

            }

        })

        // Avatar
        if (profile.value.avatar instanceof File) {

            formData.append('avatar', profile.value.avatar)

        }

        // CV
        if (profile.value.cv instanceof File) {

            formData.append('cv', profile.value.cv)

        }

        

        await $fetch('/api/developer/profile', {
            method: 'POST',
            body: formData,
        })

        navigateTo('/developer/profile')

    } catch (error: unknown) {

        const err = error as any

        errorMessage.value =
            err?.data?.message || 'Something went wrong'

        displayErrorToast.value = true

    }

}

const closeToast = () => {
    displayErrorToast.value = false
}

const handleNavigate = () => {
    navigateTo('/developer')
}

</script>
<template>
    <!-- Main Layout Container -->
    <div class="flex min-h-screen pt-16">
        <!-- Main Content Canvas -->
        <main class="flex-1 p-8 md:p-16 bg-surface">
            <div class="max-w-5xl mx-auto">
                <!-- Progress Stepper -->
                <div class="mb-12">
                    <div class="flex items-center justify-between max-w-2xl mx-auto">
                        <!-- Step 1 (Active) -->
                        <div class="flex flex-col items-center space-y-2 flex-1 cursor-pointer"
                            @click="goTo('basic-info')">
                            <div
                                class="w-10 h-10 rounded-full  flex items-center justify-center bg-primary-container text-white shadow-lg shadow-primary/20">
                                <span class="material-symbols-outlined text-sm" data-icon="person">person</span>
                            </div>
                            <span class="text-[10px] font-bold  uppercase tracking-widest"
                                :class="displayPage === 'basic-info' ? 'text-primary' : 'text-on-surface-variant'">Basic</span>
                        </div>
                        <!-- Connector -->
                        <div class="w-full h-[2px] -mt-6"
                            :class="displayPage !== 'basic-info' ? 'bg-primary-container' : 'bg-outline-variant/30'">
                        </div>
                        <!-- Step 2 -->
                        <div class="flex flex-col items-center space-y-2 flex-1 cursor-pointer"
                            @click="goTo('experience')">
                            <div class="w-10 h-10 rounded-full  flex items-center justify-center"
                                :class="displayPage !== 'basic-info' ? 'bg-primary-container text-white shadow-lg shadow-primary/20' : 'bg-surface-container'">
                                <span class="material-symbols-outlined text-sm" data-icon="work">work</span>
                            </div>
                            <span class="text-[10px] font-bold  uppercase tracking-widest"
                                :class="displayPage !== 'basic-info' ? 'text-primary' : 'text-on-surface-variant'">Experience</span>
                        </div>
                        <!-- Connector -->
                        <div class="w-full h-[2px] -mt-6"
                            :class="(displayPage === 'skills' || displayPage === 'projects') ? 'bg-primary-container' : 'bg-outline-variant/30'">
                        </div>
                        <!-- Step 3 -->
                        <div class="flex flex-col items-center space-y-2 flex-1 cursor-pointer" @click="goTo('skills')">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center "
                                :class="(displayPage === 'skills' || displayPage === 'projects') ? 'bg-primary-container text-white shadow-lg shadow-primary/20' : 'bg-surface-container text-on-surface-variant'">
                                <span class="material-symbols-outlined text-sm"
                                    data-icon="description">description</span>
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-widest"
                                :class="displayPage === 'skills' ? 'text-primary' : 'text-on-surface-variant'">Skills</span>
                        </div>
                        <div class="w-full h-[2px] -mt-6"
                            :class="displayPage === 'projects' ? 'bg-primary-container' : 'bg-outline-variant/30'">
                        </div>
                        <!-- Step 4 -->
                        <div class="flex flex-col items-center space-y-2 flex-1 cursor-pointer"
                            @click="goTo('projects')">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center "
                                :class="displayPage === 'projects' ? 'bg-primary-container text-white shadow-lg shadow-primary/20' : 'bg-surface-container text-on-surface-variant'">
                                <span class="material-symbols-outlined text-sm"
                                    data-icon="description">description</span>
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-widest"
                                :class="displayPage === 'projects' ? 'text-primary' : 'text-on-surface-variant'">Projects</span>
                        </div>
                    </div>
                </div>

                <!-- Form Section - Bento Grid Style -->
                <div v-if="displayPage === 'basic-info'">
                    <DeveloperProfileBasicInfo :profile="profile" />
                </div>
                <div v-if="displayPage === 'experience'">
                    
                    <DeveloperProfileExperience :profile="profile" />

                </div>
                <div v-if="displayPage === 'skills'">
                    <DeveloperProfileSkills :profile="profile" />
                </div>
                <div v-if="displayPage === 'projects'">
                    <DeveloperProfileProjects :profile="profile" />
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center mt-12"
                    :class="displayPage === 'basic-info' ? 'justify-end' : 'justify-between'">
                    <button
                        class="px-8 py-4 text-primary font-bold text-sm tracking-widest uppercase hover:bg-primary/5 rounded-lg transition-colors cursor-pointer"
                        @click="handleBack" v-if="displayPage !== 'basic-info'">
                        Back
                    </button>
                    <button
                        class="px-12 py-4 bg-gradient-to-br from-[#0300b2] to-[#1313ec] text-white font-bold text-sm tracking-widest uppercase rounded-lg shadow-xl shadow-primary/20 active:scale-95 transition-all cursor-pointer"
                        @click="handleContinue">
                        {{ continueTo }}
                    </button>


                </div>

                <div v-if="displayErrorToast && errorMessage">
                    <UiErrorToast :error="errorMessage" hint="your profile" @close-toast="closeToast"
                        @handle-navigate="handleNavigate" />
                </div>
            </div>


        </main>
    </div>
</template>


<style scoped>
.handle-height {
    height: calc(100vh - 85px);
    padding: 0;
}

.tonal-shift {
    background-color: #f2f4f6;
}

.create-profile-page {
    font-family: 'Inter', sans-serif;
    background-color: #f8f9fb;
}

h1,
h2,
h3 {
    font-family: 'Manrope', sans-serif;
}
</style>

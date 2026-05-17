<script setup lang="ts">

const isOpen = ref(true)

const profile = defineModel('profile')




const addNewProject = () => {
    profile.value.projects.map((p) => p.isOpen = false)

    const checkProjectsValue = profile.value.projects.every((project) =>
        (project.title !== "" && project.description !== "")
    )
    if (!checkProjectsValue) return;


    // Add new project
    profile.value.projects.push({
        id: profile.value.projects.length + 1,
        title: '',
        description: '',
        techStack: [],
        projectLink: '',
        thumbnail: null,
        isOpen: true
    })
}

const updateProject = (id: number) => {


    profile.value.projects.map((p) => {
        p.isOpen = false
    })
    profile.value.projects.forEach((p) => {
        if (p.id === id) {
            p.isOpen = true
        }
    })

}

const removeProject = (id: number) => {
    profile.value.projects = profile.value.projects.filter((p) => p.id !== id)
}
</script>

<!-- create projects -->
<template>
    <div class="create-projects">


        <DeveloperProfileProjectCard v-for="project in profile.projects" :key="project.id" :project="project"
            @open-card="updateProject" @remove-project="removeProject" />

        <div class=" flex items-center justify-between cursor-pointer" @click="addNewProject">
            <button
                class="flex items-center gap-2 text-primary font-bold text-sm tracking-tight hover:underline transition-all">
                <span class="material-symbols-outlined text-xl">add_circle</span>
                Add Another Project
            </button>

        </div>

    </div>
</template>

<style scoped>
.create-projects {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}
</style>
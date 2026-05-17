<script setup lang="ts">
import type { Project } from '~/types/project';

// This component is a placeholder for the project card form. You can expand it with props and state management as needed.
const project = defineModel<Project>('project');
// const { getImage } = useAssetImage()

// New Tag
const addTagInputIsOpen = ref<boolean>(false)
const newTag = ref<string>('')

const thumbnailInput = ref<HTMLInputElement | null>(null);
const preview = ref<string | null>(null);

function openThumbnail() {
    thumbnailInput.value?.click();
}


function handleThumbnailChange(event: Event) {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0] && project.value) {
        const file = target.files[0];
        preview.value = URL.createObjectURL(file);
        project.value.thumbnail = file;

    }
}

const addTag = (event: Event) => {

    if (event.code === 'Enter' && event.target.value.trim() !== '' || event.code == "Comma" && event.target.value.trim() !== '') {
        project.value?.techStack.push(event.target.value.trim());
        event.target.value = '';
    }

};

const removeTag = (index) => {
    project.value?.techStack.splice(index, 1);
};

const removeLastTag = (event) => {
    if (event.target.value === '' && project.value?.techStack.length > 0) {
        project.value?.techStack.pop();
    }
};

// Emits
const emit = defineEmits<{
    (e: 'open-card', id: number): void,
    (e: 'remove-project', id: number): void
}>()

const openCard = () => {
    if (project.value?.id) {
        emit('open-card', project.value.id)
    }
}

const removeProject = () => {
    if (project.value?.id) {
        emit('remove-project', project.value.id)
    }
}

onUnmounted(() => {
    if (preview.value) {
        URL.revokeObjectURL(preview.value);
    }
});

</script>

<template>
    <div class="max-w-4xl mx-auto w-full">
        <!-- Main Project Form Card -->
        <section
            class="bg-surface-container-lowest rounded-2xl shadow-[0_32px_64px_-4px_rgba(1,0,110,0.06)] p-8 md:p-12 overflow-hidden relative"
            v-if="project?.isOpen">
            <div class="space-y-10">
                <!-- Project Title -->
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-widest text-on-surface-variant ml-1">Project
                        Title</label>
                    <input
                        class="w-full bg-surface-container-low border-none border-b-2 border-outline-variant focus:border-primary focus:ring-0 px-4 py-4 rounded-t-lg transition-all text-on-surface font-medium placeholder:text-outline"
                        placeholder="e.g. Real-time Analytics Dashboard" type="text" v-model="project.title" />
                </div>
                <!-- Description & Thumbnail Row -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label
                            class="text-xs font-bold uppercase tracking-widest text-on-surface-variant ml-1">Description</label>
                        <textarea
                            class="w-full bg-surface-container-low border-none border-b-2 border-outline-variant focus:border-primary focus:ring-0 px-4 py-4 rounded-t-lg transition-all text-on-surface font-medium placeholder:text-outline resize-none h-full"
                            placeholder="Briefly describe the problem you solved and your contribution..." rows="6"
                            v-model="project.description"></textarea>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-on-surface-variant ml-1">Project
                            Thumbnail</label>
                        <div
                            class="bg-surface-container-low rounded-t-lg border-b-2 border-outline-variant flex flex-col items-center justify-center text-center p-6 h-[200px] lg:h-full group  transition-colors">
                            <div v-if="preview" class="w-full h-full overflow-hidden mb-3 flex justify-center">
                                <img :src="preview" alt="Project Thumbnail"
                                    class="object-cover rounded-xl w-[100%] h-[200px]" />
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-surface-container-lowest flex items-center justify-center mb-3 shadow-sm group-hover:scale-110 transition-transform"
                                v-else>
                                <span class="material-symbols-outlined text-2xl text-primary">cloud_upload</span>
                            </div>
                            <p class="text-on-surface-variant text-xs px-4 mb-3 font-medium">Drag and drop preview
                                or <span class="text-primary font-bold">browse</span></p>
                            <button
                                class="px-4 py-1.5 bg-white text-primary text-[10px] font-bold uppercase tracking-widest rounded-lg border border-primary/20 hover:bg-primary-fixed transition-colors cursor-pointer"
                                @click="openThumbnail">
                                Select File
                            </button>
                            <input type="file" accept="image/*" class="hidden" ref="thumbnailInput"
                                @change="handleThumbnailChange" />

                        </div>
                    </div>
                </div>
                <!-- Tech Stack & Link Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-end">
                    <div class="space-y-3">
                        <label class="text-xs font-bold uppercase tracking-widest text-on-surface-variant ml-1">Tech
                            Stack</label>
                        <div class="flex flex-wrap gap-2 items-center">

                            <div
                                class="flex gap-2 px-4 py-2 bg-secondary-fixed text-on-secondary-fixed text-xs font-bold rounded-full"
                                v-for="tag in project.techStack" :key="tag">
                                {{ tag }}
                            </div>
                            
                            <div
                                class="flex items-center bg-surface-container-low rounded-t-lg border-b-2 border-outline-variant mt-1 w-full">
                                <span class="pl-4 text-outline material-symbols-outlined text-xl">code_xml</span>

                                <input
                                    class="w-full bg-transparent border-none focus:ring-0 px-3 py-4 text-on-surface font-medium placeholder:text-outline"
                                    placeholder="new tag..." type="text" @keydown="addTag"
                                    @keydown.delete="removeLastTag" />

                                <!-- <input type="text" placeholder="Add a new tag"  /> -->

                            </div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-on-surface-variant ml-1">Project
                            Link</label>
                        <div
                            class="flex items-center bg-surface-container-low rounded-t-lg border-b-2 border-outline-variant">
                            <span class="pl-4 text-outline material-symbols-outlined text-xl">link</span>
                            <input
                                class="w-full bg-transparent border-none focus:ring-0 px-3 py-4 text-on-surface font-medium placeholder:text-outline"
                                placeholder="https://github.com/..." type="url" v-model="project.projectLink" />
                        </div>
                    </div>
                </div>
            </div>

        </section>
        <section
            class="bg-surface-container-lowest rounded-2xl shadow-[0_4px_20px_rgba(0,0,0,0.04)] border border-outline-variant/30 p-6 flex flex-col md:flex-row gap-6 relative group overflow-hidden"
            v-else>

            <div
                class="w-full md:w-36 h-24 bg-surface-container-low rounded-xl flex items-center justify-center flex-shrink-0 overflow-hidden">
                <img :src="preview" alt="Project thumbnail" v-if="preview" class="w-full h-full">
                <span class="material-symbols-outlined text-3xl text-outline-variant" v-else>image</span>
            </div>
            <div class="flex-1">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-headline font-bold text-xl text-on-surface">{{ project?.title }}</h3>
                    <div class="flex gap-2">
                        <button class="p-2 hover:bg-surface-container-high rounded-full text-outline transition-colors">
                            <span class="material-symbols-outlined text-xl" @click="openCard">edit</span>
                        </button>
                        <button
                            class="p-2 hover:bg-error-container hover:text-error rounded-full text-outline transition-colors">
                            <span class="material-symbols-outlined text-xl" @click="removeProject">delete</span>
                        </button>
                    </div>
                </div>
                <p class="text-on-surface-variant text-sm line-clamp-2 mb-4">
                    {{ project?.description }}
                </p>
                <div class="flex flex-wrap gap-2">


                    <span
                        class="px-3 py-1 bg-secondary-fixed text-on-secondary-fixed text-[10px] font-bold uppercase tracking-wider rounded-full"
                        v-for="tag in project?.techStack" :key="tag">{{ tag }}</span>
                </div>
            </div>
            <div class="absolute top-0 left-0 w-1 h-full bg-primary/20"></div>
        </section>
    </div>
</template>
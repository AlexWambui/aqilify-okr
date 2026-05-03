<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { Pencil, Plus } from 'lucide-vue-next';
import yearsRoutes from '@/routes/years';

interface Year {
    id: number;
    year: number;
}

interface Props {
    years: Year[];
    current_year: number | null;
}

const props = defineProps<Props>();

const goToPreviousYear = () => {
    if (!props.current_year) return;
    
    const currentIndex = props.years.findIndex(y => y.year === props.current_year);
    if (currentIndex > 0) {
        const previousYear = props.years[currentIndex - 1].year;
        router.get('/okrs', { year: previousYear }, {
            preserveState: true,
            preserveScroll: true,
            replace: true, // This replaces the URL instead of pushing a new entry
        });
    }
};

const goToNextYear = () => {
    if (!props.current_year) return;
    
    const currentIndex = props.years.findIndex(y => y.year === props.current_year);
    if (currentIndex < props.years.length - 1) {
        const nextYear = props.years[currentIndex + 1].year;
        router.get('/okrs', { year: nextYear }, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        });
    }
};

// Simple display logic
const displayYear = () => {
    if (props.years.length === 0) return null;
    if (props.current_year) return props.current_year;
    return props.years[0]?.year;
};
</script>

<template>
    <div class="app-container OkrsPage">
        <div class="header">
            <div class="navbar">
                <div class="year-selector">
                    <button 
                        class="year-btn" 
                        @click="goToPreviousYear"
                        :disabled="!props.current_year || props.years.findIndex(y => y.year === props.current_year) <= 0"
                    >
                        &#8249;
                    </button>
                    
                    <span v-if="props.years.length === 0" class="empty-text">
                        Create Years to get started
                    </span>
                    <span v-else class="year-text">
                        {{ displayYear() }}
                    </span>
                    
                    <button 
                        class="year-btn" 
                        @click="goToNextYear"
                        :disabled="!props.current_year || props.years.findIndex(y => y.year === props.current_year) >= props.years.length - 1"
                    >
                        &#8250;
                    </button>
                    
                    <div class="actions">
                        <Link 
                            v-if="props.current_year"
                            :href="yearsRoutes.edit(props.current_year).url" 
                            title="Edit Year" 
                            class="btn"
                        >
                            <Pencil class="icon" />
                        </Link>
                        <Link :href="yearsRoutes.create().url" title="Add Year" class="btn">
                            <Plus class="icon" />
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
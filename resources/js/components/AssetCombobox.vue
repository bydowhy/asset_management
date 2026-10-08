<script setup lang="ts">
import { ref, watch } from 'vue';
import axios from 'axios';
import { Check, ChevronsUpDown } from '@lucide/vue';
import { Button } from '@/components/ui/button';
import {
    Command, CommandEmpty, CommandGroup, CommandInput, CommandItem, CommandList,
} from '@/components/ui/command';
import {
    Popover, PopoverContent, PopoverTrigger,
} from '@/components/ui/popover';
import { cn } from '@/lib/utils';

type AssetOption = {
    id: string;
    asset_code: string;
    asset_type_name: string | null;
    manufacturer: string | null;
    model: string | null;
    status: string;
};

const props = withDefaults(defineProps<{
    modelValue: string;
    placeholder?: string;
    excludeAssetId?: string; // untuk replace: exclude asset yang sedang diganti
}>(), {
    placeholder: 'Pilih asset...',
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
    (e: 'select', asset: AssetOption): void;
}>();

const open = ref(false);
const search = ref('');
const options = ref<AssetOption[]>([]);
const loading = ref(false);
const selectedLabel = ref<string | null>(null);

let debounce: ReturnType<typeof setTimeout> | null = null;

async function fetchOptions(q: string) {
    loading.value = true;
    try {
        const { data } = await axios.get('/assets/search', { params: { q } });
        // Filter out excluded asset (untuk replace)
        options.value = props.excludeAssetId
            ? data.filter((a: AssetOption) => a.id !== props.excludeAssetId)
            : data;
    } catch (e) {
        options.value = [];
    } finally {
        loading.value = false;
    }
}

watch(search, (q) => {
    if (debounce) clearTimeout(debounce);
    debounce = setTimeout(() => fetchOptions(q), 250);
});

watch(open, (isOpen) => {
    if (isOpen) fetchOptions(search.value);
});

// Sinkronisasi label saat modelValue di-set dari luar
watch(() => props.modelValue, (val) => {
    if (!val) {
        selectedLabel.value = null;
        return;
    }
    const found = options.value.find((o) => o.id === val);
    if (found) {
        selectedLabel.value = `${found.asset_code} — ${found.asset_type_name ?? ''}`;
    }
}, { immediate: true });

function pick(item: AssetOption) {
    emit('update:modelValue', item.id);
    emit('select', item);
    selectedLabel.value = `${item.asset_code} — ${item.asset_type_name ?? ''}`;
    open.value = false;
    search.value = '';
}
</script>

<template>
    <Popover v-model:open="open">
        <PopoverTrigger as-child>
            <Button
                variant="outline"
                role="combobox"
                :aria-expanded="open"
                class="w-full justify-between"
                type="button"
            >
                <span v-if="selectedLabel" class="truncate">{{ selectedLabel }}</span>
                <span v-else class="text-muted-foreground">{{ placeholder }}</span>
                <ChevronsUpDown class="ml-2 h-4 w-4 shrink-0 opacity-50" />
            </Button>
        </PopoverTrigger>
        <PopoverContent class="w-105 p-0" align="start">
            <Command should-filter="false">
                <CommandInput
                    v-model="search"
                    placeholder="Ketik asset code / manufacturer / model..."
                />
                <CommandList>
                    <CommandEmpty v-if="loading" class="py-6 text-center text-sm">
                        Mencari...
                    </CommandEmpty>
                    <CommandEmpty v-else class="py-6 text-center text-sm">
                        Tidak ada asset yang cocok.
                    </CommandEmpty>
                    <CommandGroup>
                        <CommandItem
                            v-for="item in options"
                            :key="item.id"
                            :value="item.id"
                            @select="pick(item)"
                        >
                            <Check
                                :class="cn(
                                    'mr-2 h-4 w-4',
                                    modelValue === item.id ? 'opacity-100' : 'opacity-0'
                                )"
                            />
                            <div class="flex flex-col">
                                <span class="font-medium">{{ item.asset_code }}</span>
                                <span class="text-xs text-muted-foreground">
                                    {{ item.asset_type_name ?? '—' }}
                                    <span v-if="item.manufacturer"> · {{ item.manufacturer }}</span>
                                    <span v-if="item.model"> {{ item.model }}</span>
                                </span>
                            </div>
                        </CommandItem>
                    </CommandGroup>
                </CommandList>
            </Command>
        </PopoverContent>
    </Popover>
</template>
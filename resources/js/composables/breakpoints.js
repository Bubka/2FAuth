import { useBreakpoints } from '@vueuse/core'

/**
 * Gives breakpoint for settings views
 */
export function useSettingsBreakpoints() {
    
    const breakpoints = useBreakpoints({
        laptop
            : 1024,
    })

    const isLaptop = breakpoints.laptop

    return { isLaptop }
}
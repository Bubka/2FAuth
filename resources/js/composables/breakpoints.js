import { useBreakpoints } from '@vueuse/core'

/**
 * Gives breakpoint for settings views
 */
export function useSettingsBreakpoints() {
    
    const breakpoints = useBreakpoints({
        laptop
            : 1024,
        desktop
            : 1215,
    })

    const isLaptop = breakpoints.laptop
    const isDesktop = breakpoints.desktop

    return { isLaptop, isDesktop }
}
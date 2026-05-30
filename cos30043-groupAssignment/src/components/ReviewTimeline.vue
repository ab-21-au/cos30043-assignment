<template>
    <section class="timeline-section" aria-label="Rating Timeline">
        <h3 class="section-label">Rating Over Time</h3>

        <p v-if="snapshots.length === 0" class="timeline-empty">
            Not enough reviews yet to build a timeline.
        </p>

        <div v-else class="timeline-wrapper">

            <!-- Canvas Graph -->
            <div class="canvas-container" ref="canvasContainer">
                <canvas
                    ref="canvasEl"
                    @mousemove="onMouseMove"
                    @mouseleave="onMouseLeave"
                    @click="onCanvasClick"
                    aria-label="Interactive Rating Timeline Graph"
                ></canvas>
            </div>

            <!-- Legend -->
            <div class="graph-legend" aria-label="Graph Legend">
                <span class="legend-item legend-user">
                    <span class="legend-line solid"></span> User Reviews
                </span>
                <span class="legend-item legend-tmdb">
                    <span class="legend-line dashed"></span> TMDB Global Rating
                </span>
            </div>

            <!-- Tooltip panel - displayed when a point is clicked -->
            <transition name="fade">
                <div v-if="activeSnapshot" class="timeline-tooltip" aria-label="Polite">
                    <div class="tooltip-header">
                        <span class="tooltip-period">{{ activeSnapshot.period }}</span>
                        <span class="tooltip-count">{{ activeSnapshot.reviewCount }} review{{ activeSnapshot.reviewCount !== 1 ? 's' : '' }}</span>
                    </div>

                    <div class="tooltip-score" v-if="activeSnapshot.avgScore !== null">
                        Overall: <strong>{{ activeSnapshot.avgScore.toFixed(1) }} / 5</strong>
                        <span class="score-label">{{ scoreLabel(activeSnapshot.avgScore) }}</span>
                    </div>
            
                    <div class="tooltip-subratings" v-if="activeSnapshot.avgPlot || activeSnapshot.avgActing || activeSnapshot.avgPacing">
                        <span v-if="activeSnapshot.avgPlot">Plot {{ activeSnapshot.avgPlot.toFixed(1) }}</span>
                        <span v-if="activeSnapshot.avgActing">Acting {{ activeSnapshot.avgActing.toFixed(1) }}</span>
                        <span v-if="activeSnapshot.avgPacing">Pacing {{ activeSnapshot.avgPacing.toFixed(1) }}</span>
                    </div>
            
                    <div class="tooltip-samples" v-if="activeSnapshot.samples.length">
                        <div v-for="(s, i) in activeSnapshot.samples" :key="i" class="tooltip-sample">
                            <span class="sample-user">{{ s.username }}</span>
                            <span class="sample-rating">{{ s.rating }}</span>
                            <p class="sample-content">{{ s.content }}</p>
                        </div>
                    </div>
                </div>
            </transition>

        </div>
    </section>
</template>


<script setup>
    import { ref, inject, watch, onMounted, onUnmounted, nextTick } from 'vue'
    import { useReviewTimeline } from '../composables/UserReviewTimeline'
    
    const movie = inject('movie', ref({ globalRating: null, year: null, releaseDate: null }))   
    const reviews = inject('reviews', ref([]))
    
    const releaseDate = ref(movie.value?.releaseDate || null)
    watch(() => movie.value?.releaseDate, d => { if (d) releaseDate.value = d }) // Watches for the actual release date and updates accordingly
    
    const { snapshots } = useReviewTimeline(reviews, releaseDate) // Composable call
    
    const canvasEl = ref(null) // refs attached to DOM
    const canvasContainer = ref(null) // refs attached to DOM
    const activeSnapshot = ref(null) // Dot the user clicks
    const hoveredIndex = ref(null) // Dot the mouse is on
    
    const PADDING = { top: 30, right: 20, bottom: 50, left: 50 } // Spacing for Canvas drawing
    
    function scoreLabel(score) {
        if (score >= 4.5) { return
            '🔥 Peak'
        }
        if (score >= 3.5) { 
            return '👍 Good'
        }
        if (score >= 2.5) { 
            return '😐 Mid'
        }
        return '👎 Rough'
    }
    
    // Reads a CSS variable from the canvas element 
    function getCSSVar(name) {
        return getComputedStyle(canvasEl.value).getPropertyValue(name).trim()
    }
    
    // Calculates the coordinates of each snapshot dot
    function getPoints(canvas) {
        const w = canvas.width - PADDING.left - PADDING.right // Uses the space left to place dots evenly
        const h = canvas.height - PADDING.top - PADDING.bottom
        const count = snapshots.value.length
        
        return snapshots.value.map((snap, i) => ({
            x: PADDING.left + (count === 1 ? w / 2 : (i / (count - 1)) * w),
            y: snap.avgScore !== null 
            ? PADDING.top + h - ((snap.avgScore / 5) * h) // Takes average score out of 5 and maps it to the height
            : null,
            snap
        }))
    }
    
    // Clears he canvas and redraws everything
    function drawGraph() {
        const canvas = canvasEl.value
        if (!canvas || !snapshots.value.length) return
        
        // Sets container values
        const container = canvasContainer.value
        canvas.width = container.clientWidth
        canvas.height = Math.max(220, container.clientWidth * 0.38)
        
        const ctx = canvas.getContext('2d')
        const w = canvas.width - PADDING.left - PADDING.right
        const h = canvas.height - PADDING.top - PADDING.bottom
        
        ctx.clearRect(0, 0, canvas.width, canvas.height)
        
        // CSS variables for colors, found in 'styles.css'
        const textColor   = getCSSVar('--rot-text') // axis labels and score text above dots
        const gridColor   = getCSSVar('--rot-grid') // faint horizontal grid lines
        const lineColor   = getCSSVar('--rot-user-line') // user review connecting line and dot fill
        const hoverColor  = getCSSVar('--rot-hover') // highlighted dot and label on hover/click
        const criticColor = getCSSVar('--rot-critic') // TMDB dashed reference line and its label
        const dotBorder   = getCSSVar('--rot-dot-border') // small border ring drawn around each dot
        
        // Y axis grid lines and labels (1–5)
        ctx.font = '11px sans-serif'
        ctx.fillStyle = textColor
        ctx.textAlign = 'right'
        for (let score = 1; score <= 5; score++) {
            const y = PADDING.top + h - ((score / 5) * h)
            ctx.strokeStyle = gridColor
            ctx.lineWidth = 1
            ctx.beginPath()
            ctx.moveTo(PADDING.left, y)
            ctx.lineTo(PADDING.left + w, y)
            ctx.stroke()
            ctx.fillText(score, PADDING.left - 8, y + 4)
        }
        
        // TMDB global rating as a dashed reference line
        // TMDB scores are out of 10, halved to match 1–5 scale
        const tmdbRaw = parseFloat(movie.value?.globalRating)
        const tmdbScore = !isNaN(tmdbRaw) ? tmdbRaw / 2 : null // Is set as null if it is not a valid score
        
        // Loops through all points and draws the dot, score label and period table below
        if (tmdbScore !== null) {
            const tmdbY = PADDING.top + h - ((tmdbScore / 5) * h)
            ctx.beginPath()
            ctx.setLineDash([6, 4])
            ctx.strokeStyle = criticColor
            ctx.lineWidth = 1.5
            ctx.moveTo(PADDING.left, tmdbY)
            ctx.lineTo(PADDING.left + w, tmdbY)
            ctx.stroke()
            ctx.setLineDash([])
            ctx.fillStyle = criticColor
            ctx.font = '10px sans-serif'
            ctx.textAlign = 'right'
            ctx.fillText(`TMDB ${tmdbRaw}`, PADDING.left + w, tmdbY - 5)
        }
        
        const points = getPoints(canvas)
        const validPoints = points.filter(p => p.y !== null)
        
        
        
        // User review connecting line
        const gradient = ctx.createLinearGradient(PADDING.left, 0, PADDING.left + w, 0)
        gradient.addColorStop(0, lineColor + '88')
        gradient.addColorStop(1, lineColor)
        
        ctx.beginPath()
        ctx.strokeStyle = gradient
        ctx.lineWidth = 2.5
        ctx.lineJoin = 'round'
        validPoints.forEach((p, i) => {
            if (i === 0) ctx.moveTo(p.x, p.y)
            else ctx.lineTo(p.x, p.y)
        })
        ctx.stroke()
        
        // Fill area under the line
        ctx.beginPath()
        validPoints.forEach((p, i) => {
            if (i === 0) ctx.moveTo(p.x, p.y)
            else ctx.lineTo(p.x, p.y)
        })
        ctx.lineTo(validPoints[validPoints.length - 1].x, PADDING.top + h)
        ctx.lineTo(validPoints[0].x, PADDING.top + h)
        ctx.closePath()
        const fill = ctx.createLinearGradient(0, PADDING.top, 0, PADDING.top + h)
        fill.addColorStop(0, lineColor + '33')
        fill.addColorStop(1, lineColor + '00')
        ctx.fillStyle = fill
        ctx.fill()
        
        // Dots and x-axis labels
        points.forEach((p, i) => {
            const isHovered = hoveredIndex.value === i
            const isActive = activeSnapshot.value?.period === p.snap.period
        
            ctx.fillStyle = (isActive || isHovered) ? hoverColor : textColor
            ctx.font = isActive ? 'bold 10px sans-serif' : '10px sans-serif'
            ctx.textAlign = 'center'
        
            const words = p.snap.period.split(' ')
            const mid = Math.ceil(words.length / 2)
            ctx.fillText(words.slice(0, mid).join(' '), p.x, canvas.height - 24)
            const line2 = words.slice(mid).join(' ')
            if (line2) ctx.fillText(line2, p.x, canvas.height - 12)
        
            if (p.y === null) return
        
            // Glow ring on hover/active
            if (isHovered || isActive) {
                ctx.beginPath()
                ctx.arc(p.x, p.y, 10, 0, Math.PI * 2)
                ctx.fillStyle = hoverColor + '33'
                ctx.fill()
            }
        
            // Main dot
            ctx.beginPath()
            ctx.arc(p.x, p.y, isHovered || isActive ? 7 : 5, 0, Math.PI * 2)
            ctx.fillStyle = isHovered || isActive ? hoverColor : lineColor
            ctx.fill()
            ctx.strokeStyle = dotBorder
            ctx.lineWidth = 2
            ctx.stroke()
        
            // Score label above dot
            if (p.snap.avgScore !== null) {
                ctx.fillStyle = isHovered || isActive ? hoverColor : textColor
                ctx.font = 'bold 11px sans-serif'
                ctx.textAlign = 'center'
                ctx.fillText(p.snap.avgScore.toFixed(1), p.x, p.y - 14)
            }
        })
    }
    
    // Calculates the dot which the mouse is the closest too
    function getNearestPoint(e) {
        const canvas = canvasEl.value
        const rect = canvas.getBoundingClientRect()
        const mouseX = e.clientX - rect.left
        const mouseY = e.clientY - rect.top
        const points = getPoints(canvas)
        
        let nearest = null
        let minDist = Infinity
        
        points.forEach((p, i) => { // Loops through all points and calculate the distance to each one
            if (p.y === null) return
            const dist = Math.sqrt((mouseX - p.x) ** 2 + (mouseY - p.y) ** 2)
            if (dist < minDist) { minDist = dist; nearest = i }
        })
        
        return minDist < 30 ? nearest : null // Returns the nearest point if its within 30 pixels
    }
    
    // Uses nearest point to update hoveredIndex, and redraws graph
    function onMouseMove(e) {
        const idx = getNearestPoint(e)
        if (idx !== hoveredIndex.value) {
            hoveredIndex.value = idx
            canvasEl.value.style.cursor = idx !== null ? 'pointer' : 'default'
            drawGraph()
        }
    }
    
    // Sets hoveredIndex to null and redraws graph
    function onMouseLeave() {
        hoveredIndex.value = null
        canvasEl.value.style.cursor = 'default'
        drawGraph()
    }
    
    // Toggles the active snapshot to show/hide the tooltip panel
    function onCanvasClick(e) {
        const idx = getNearestPoint(e)
        if (idx !== null) {
            const snap = snapshots.value[idx]
            activeSnapshot.value = activeSnapshot.value?.period === snap.period ? null : snap
        }
    }
    
    // Redraws the graph whenever the snapshot changes
    watch(snapshots, async () => {
        await nextTick() // Waits for vue to finish DOM updates before redrawing
        drawGraph()
    }, { deep: true }) // Looks for changes in the array
    
    const resizeObserver = new ResizeObserver(() => drawGraph())
    
    // Runs once the DOMs ready
    onMounted(async () => {
        await nextTick()
        drawGraph()
        if (canvasContainer.value) resizeObserver.observe(canvasContainer.value) // native browser API that watches container size changes and redraws the graph
    })
    
    onUnmounted(() => resizeObserver.disconnect()) // Cleans up the graph to prevent memory leaks when clicking onto another component/page
</script>


<style scoped>
    
    .timeline-section {
        margin-top: 15px;
    }
    
    .timeline-wrapper {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    
    .canvas-container {
        width: 90%;
        align-self: center;
        border-radius: 5px;
        background: var(--bg-surface);
        border: 1px solid var(--bg-primary);
        padding: 10px;
        box-sizing: border-box;
    }
    
    .canvas-container canvas {
        display: block;
        width: 100%;
    }
    
    .timeline-empty {
        width: 90%;
        margin: auto;
        margin-bottom: 30px;
        border-radius: 5px;
        background: var(--bg-surface);
        border: 1px solid var(--bg-primary);
        color: var(--text-primary);
        padding: 10px;
        font-size: 15px;
    }
    
    /* Legend */
    .graph-legend {
        width: 90%;
        margin: auto;
        margin-bottom: 30px;
        border-radius: 5px;
        background: var(--bg-surface);
        border: 1px solid var(--bg-primary);
        display: flex;
        font-size: 14px;
        padding-left: 2px;
    }
    
    .legend-item {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--text-primary);
        margin-right: 10px;
    }
    
    .legend-line {
        display: inline-block;
        width: 24px;
        height: 2px;
        margin-left: 5px;
    }
    
    .legend-user .legend-line {
        background: var(--rot-user-line);
    }
    
    .legend-tmdb .legend-line {
        background: none;
        border-top: 2px dashed var(--rot-critic);
    }
    
    /* Tooltip panel */
    .timeline-tooltip {
        width: 75%;
        margin: auto;
        border-radius: 5px;
        background: var(--bg-surface);
        border: 1px solid var(--bg-primary);
        padding: 15px 20px;
        margin-bottom: 45px;
    }
    
    .tooltip-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 5px;
    }
    
    .tooltip-period {
        font-weight: 600;
        font-size: 22px;
    }
    
    .tooltip-count {
        font-size: 15px;
        color: var(--text-secondary);
    }
    
    .tooltip-score {
        font-size: 15px;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .score-label {
        font-size: 14px;
        opacity: 0.75;
    }
    
    .tooltip-subratings {
        display: flex;
        gap: 12px;
        font-size: 14px;
        color: var(--text-secondary);
        margin-bottom: 13px;
    }
    
    .tooltip-samples {
        display: flex;
        flex-direction: column;
        gap: 15px;
        border-top: 3px solid var(--accent);
        padding-top: 10px;
    }
    
    .tooltip-sample {
        font-size: 14px;
    }
    
    .sample-user {
        font-weight: 600;
        margin-right: 10px;
    }
    
    .sample-rating {
        font-size: 13px;
        opacity: 0.65;
    }
    
    .sample-content {
        margin: 2px 0 0;
        color: var(--text-primary);
        line-height: 1.5;
    }
    
    /* Transition for tooltip appearing */
    .fade-enter-active,
    .fade-leave-active {
    transition: opacity 0.2s ease, transform 0.4s ease;
    }
    .fade-enter-from,
    .fade-leave-to {
        opacity: 0;
        transform: translateY(6px);
    }
</style>
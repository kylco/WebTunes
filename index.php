<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kastor Radio - Modern Web Player</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    color: #fff;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.player-container {
    width: 100%;
    max-width: 450px;
    background: rgba(25, 25, 35, 0.8);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4);
    overflow: hidden;
    position: relative;
}

.header {
    padding: 25px 25px 15px;
    text-align: center;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.station-name {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 5px;
    background: linear-gradient(90deg, #6a11cb 0%, #2575fc 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.station-tagline {
    font-size: 0.9rem;
    color: #a0a0c0;
    margin-bottom: 10px;
}

.album-art {
    width: 220px;
    height: 220px;
    margin: 20px auto;
    border-radius: 50%;
    background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
    display: flex;
    justify-content: center;
    align-items: center;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
    position: relative;
    overflow: hidden;
    animation: rotate 20s linear infinite;
    animation-play-state: paused;
}

.album-art.playing {
    animation-play-state: running;
}

.album-art::before {
    content: '';
    position: absolute;
    width: 50%;
    height: 50%;
    background: rgba(25, 25, 35, 0.8);
    border-radius: 50%;
    z-index: 2;
}

.album-art img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    position: relative;
    z-index: 1;
}

.visualizer {
    display: flex;
    justify-content: center;
    align-items: flex-end;
    height: 60px;
    margin: 20px 0;
    gap: 3px;
}

.bar {
    width: 5px;
    background: linear-gradient(to top, #6a11cb, #2575fc);
    border-radius: 3px 3px 0 0;
    height: 10px;
    animation: equalizer 1.5s ease infinite alternate;
}

.bar:nth-child(2) { animation-delay: 0.1s; }
.bar:nth-child(3) { animation-delay: 0.2s; }
.bar:nth-child(4) { animation-delay: 0.3s; }
.bar:nth-child(5) { animation-delay: 0.4s; }
.bar:nth-child(6) { animation-delay: 0.5s; }
.bar:nth-child(7) { animation-delay: 0.6s; }
.bar:nth-child(8) { animation-delay: 0.7s; }
.bar:nth-child(9) { animation-delay: 0.8s; }
.bar:nth-child(10) { animation-delay: 0.9s; }

.track-info {
    text-align: center;
    padding: 0 25px;
    margin-bottom: 20px;
}

.track-title {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 5px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.track-artist {
    font-size: 0.9rem;
    color: #a0a0c0;
}

.progress-container {
    padding: 0 25px;
    margin-bottom: 20px;
}

.progress-bar {
    width: 100%;
    height: 6px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
    cursor: pointer;
    position: relative;
}

.progress {
    height: 100%;
    background: linear-gradient(90deg, #6a11cb 0%, #2575fc 100%);
    border-radius: 3px;
    width: 0%;
    transition: width 0.1s linear;
}

.time-display {
    display: flex;
    justify-content: space-between;
    margin-top: 5px;
    font-size: 0.8rem;
    color: #a0a0c0;
}

.controls {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 15px 25px 25px;
    gap: 25px;
}

.control-btn {
    background: none;
    border: none;
    color: #fff;
    font-size: 1.2rem;
    cursor: pointer;
    transition: all 0.2s ease;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.control-btn:hover {
    background: rgba(255, 255, 255, 0.1);
}

.control-btn.play-pause {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
    font-size: 1.5rem;
    box-shadow: 0 5px 15px rgba(106, 17, 203, 0.4);
}

.control-btn.play-pause:hover {
    transform: scale(1.05);
}

.volume-container {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0 25px 20px;
}

.volume-slider {
    flex: 1;
    height: 5px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
    outline: none;
    -webkit-appearance: none;
}

.volume-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #6a11cb;
    cursor: pointer;
}

.status {
    text-align: center;
    padding: 10px;
    font-size: 0.9rem;
    color: #a0a0c0;
    min-height: 20px;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@keyframes equalizer {
    0% { height: 10px; }
    100% { height: 40px; }
}

@media (max-width: 480px) {
    .player-container {
        max-width: 100%;
    }
    
    .album-art {
        width: 180px;
        height: 180px;
    }
    
    .controls {
        gap: 15px;
    }
}
</style>
</head>
<body>
<div class="player-container">
<div class="header">
<h1 class="station-name">Kastor Radio</h1>
<p class="station-tagline">Your favorite tunes, 24/7</p>
</div>

<div class="album-art" id="albumArt">
<i class="fas fa-music" style="font-size: 3rem; color: rgba(255,255,255,0.7); z-index: 3; position: relative;"></i>
</div>

<div class="visualizer" id="visualizer">
<div class="bar"></div>
<div class="bar"></div>
<div class="bar"></div>
<div class="bar"></div>
<div class="bar"></div>
<div class="bar"></div>
<div class="bar"></div>
<div class="bar"></div>
<div class="bar"></div>
<div class="bar"></div>
</div>

<div class="track-info">
<div class="track-title" id="trackTitle">Loading station...</div>
<div class="track-artist" id="trackArtist">Kastor Radio</div>
</div>

<div class="progress-container">
<div class="progress-bar" id="progressBar">
<div class="progress" id="progress"></div>
</div>
<div class="time-display">
<span id="currentTime">0:00</span>
<span id="duration">Live Stream</span>
</div>
</div>

<div class="controls">
<button class="control-btn" id="prevBtn">
<i class="fas fa-step-backward"></i>
</button>
<button class="control-btn play-pause" id="playPauseBtn">
<i class="fas fa-play" id="playIcon"></i>
</button>
<button class="control-btn" id="nextBtn">
<i class="fas fa-step-forward"></i>
</button>
</div>

<div class="volume-container">
<i class="fas fa-volume-up"></i>
<input type="range" class="volume-slider" id="volumeSlider" min="0" max="100" value="80">
</div>

<div class="status" id="status">Click play to start listening</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Player elements
    const audioPlayer = new Audio();
    const playPauseBtn = document.getElementById('playPauseBtn');
    const playIcon = document.getElementById('playIcon');
    const volumeSlider = document.getElementById('volumeSlider');
    const progressBar = document.getElementById('progressBar');
    const progress = document.getElementById('progress');
    const currentTimeEl = document.getElementById('currentTime');
    const durationEl = document.getElementById('duration');
    const albumArt = document.getElementById('albumArt');
    const trackTitle = document.getElementById('trackTitle');
    const trackArtist = document.getElementById('trackArtist');
    const statusEl = document.getElementById('status');
    const visualizer = document.getElementById('visualizer');
    
    // Set the stream URL
    const streamUrl = 'SOUNDFILE.';
audioPlayer.src = streamUrl;
audioPlayer.crossOrigin = "anonymous";

// For live streams, we don't have a duration
durationEl.textContent = 'Live Stream';

// Play/Pause functionality
playPauseBtn.addEventListener('click', function() {
    if (audioPlayer.paused) {
        playStream();
    } else {
        pauseStream();
    }
});

function playStream() {
    audioPlayer.play()
    .then(() => {
        playIcon.className = 'fas fa-pause';
    albumArt.classList.add('playing');
    statusEl.textContent = 'Now playing';
    updateTrackInfo();
    })
    .catch(error => {
        console.error('Playback failed:', error);
        statusEl.textContent = 'Playback failed. Click play to try again.';
    });
}

function pauseStream() {
    audioPlayer.pause();
    playIcon.className = 'fas fa-play';
albumArt.classList.remove('playing');
statusEl.textContent = 'Paused';
}

// Volume control
volumeSlider.addEventListener('input', function() {
    audioPlayer.volume = this.value / 100;
});

// Update progress (for live streams this is just an indicator)
audioPlayer.addEventListener('timeupdate', function() {
    if (audioPlayer.duration) {
        const progressPercent = (audioPlayer.currentTime / audioPlayer.duration) * 100;
        progress.style.width = `${progressPercent}%`;
        
        // Format time
        currentTimeEl.textContent = formatTime(audioPlayer.currentTime);
    } else {
        // For live streams, just show elapsed time
        currentTimeEl.textContent = formatTime(audioPlayer.currentTime);
    }
});

// Click on progress bar to seek (not applicable for live streams)
progressBar.addEventListener('click', function(e) {
    if (audioPlayer.duration) {
        const rect = this.getBoundingClientRect();
        const percent = (e.clientX - rect.left) / rect.width;
        audioPlayer.currentTime = percent * audioPlayer.duration;
    }
});

// Format time helper
function formatTime(seconds) {
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${mins}:${secs < 10 ? '0' : ''}${secs}`;
}

// Update track info (simulated for demo)
function updateTrackInfo() {
    // In a real implementation, you would fetch metadata from the stream
    // For now, we'll use simulated data
    const tracks = [
        { title: "Summer Vibes", artist: "Chill Beats Collective" },
        { title: "Midnight Drive", artist: "Synthwave Heroes" },
        { title: "Urban Dreams", artist: "City Lights" },
        { title: "Ocean View", artist: "Beachside Producers" },
        { title: "Mountain High", artist: "Nature Sounds" }
    ];
    
    const currentTrack = tracks[Math.floor(Math.random() * tracks.length)];
    trackTitle.textContent = currentTrack.title;
    trackArtist.textContent = currentTrack.artist;
    
    // Change track info periodically to simulate a live stream
    setInterval(() => {
        const newTrack = tracks[Math.floor(Math.random() * tracks.length)];
        trackTitle.textContent = newTrack.title;
        trackArtist.textContent = newTrack.artist;
    }, 15000);
}

// Visualizer animation
const bars = document.querySelectorAll('.bar');
function animateBars() {
    bars.forEach(bar => {
        const randomHeight = Math.random() * 30 + 10;
        bar.style.height = `${randomHeight}px`;
    });
}

// Only animate when playing
audioPlayer.addEventListener('play', () => {
    visualizerInterval = setInterval(animateBars, 200);
});

audioPlayer.addEventListener('pause', () => {
    clearInterval(visualizerInterval);
    bars.forEach(bar => {
        bar.style.height = '10px';
    });
});

let visualizerInterval;

// Handle errors
audioPlayer.addEventListener('error', function(e) {
    console.error('Audio error:', e);
    statusEl.textContent = 'Error loading stream. Please try again.';
});

// Handle waiting/buffering
audioPlayer.addEventListener('waiting', function() {
    statusEl.textContent = 'Buffering...';
});

audioPlayer.addEventListener('canplay', function() {
    statusEl.textContent = 'Ready to play';
});

// Initialize
audioPlayer.volume = volumeSlider.value / 100;
});
</script>
</body>
</html>

<template>
  <div class="app">
    <div class="header">
      <h1>SAAS Entry Level Product</h1>
      <img src="https://static.danads.com/content/uploads/2020/07/da_logo-r-mono-green.png" alt="Logo" class="logo" />
    </div>
    <div class="input-section">
      <input
        type="url"
        placeholder="Enter your link here..."
        v-model="linkInput"
        class="link-input"
      />
      <button @click="processStep1" class="action-btn" :disabled="isGenerating">
        {{ isGenerating ? 'Generating...' : 'Generate' }}
      </button>
    </div>
    <div class="preview">
      <div v-if="errorMessage" class="error">
        {{ errorMessage }}
      </div>
      <div v-else-if="isGenerating" class="loading-info">
        <div class="request-info">
          <div class="endpoint">POST /api/step1</div>
          <div class="payload">
            <div class="payload-label">Request payload:</div>
            <div class="payload-data">
              <VueJsonPretty :data="{ url: linkInput }" />
            </div>
          </div>
          <div class="timer">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"></circle>
              <polyline points="12,6 12,12 16,14"></polyline>
            </svg>
            {{ elapsedTime }}s elapsed...
          </div>
        </div>
      </div>
      <div v-else-if="previewData" class="json-output">
        <VueJsonPretty :data="previewData" />
        <div class="loading-time">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <polyline points="12,6 12,12 16,14"></polyline>
          </svg>
          Loaded in {{ loadingTime }}ms
        </div>
      </div>
      <div v-else class="placeholder">
        Preview
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import VueJsonPretty from 'vue-json-pretty'
import 'vue-json-pretty/lib/styles.css'

const linkInput = ref('https://www.brompton.com/p/817/c-line-explore-23')
const isGenerating = ref(false)
const previewData = ref(null)
const errorMessage = ref('')
const loadingTime = ref(0)
const elapsedTime = ref(0)

const processStep1 = async () => {
  if (linkInput.value && !isGenerating.value) {
    isGenerating.value = true
    errorMessage.value = ''
    previewData.value = null
    loadingTime.value = 0
    elapsedTime.value = 0

    const startTime = performance.now()

    // Timer to update elapsed time every 100ms
    const timer = setInterval(() => {
      const currentTime = performance.now()
      elapsedTime.value = parseFloat(((currentTime - startTime) / 1000).toFixed(1))
    }, 100)

    try {
      const response = await axios.post('/api/step1', {
        url: linkInput.value
      })

      const endTime = performance.now()
      loadingTime.value = Math.round(endTime - startTime)
      previewData.value = response.data
    } catch (error) {
      errorMessage.value = error.response?.data?.message || 'An error occurred'
      console.error('Error:', error)
    } finally {
      clearInterval(timer)
      isGenerating.value = false
    }
  }
}
</script>

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body, html {
  margin: 0;
  padding: 0;
  background: #0d1117;
}
.app {
  width: 100vw;
  height: 100vh;
  padding: 1rem;
  font-family: 'Courier New', Monaco, Consolas, monospace;
  background: #0d1117;
  color: #c9d1d9;
  box-sizing: border-box;
}

.header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 1.5rem;
}

.logo {
  height: 32px;
  width: auto;
}

h1 {
  color: #58a6ff;
  font-size: 1.2rem;
  font-weight: normal;
  margin: 0;
}

h1::before {
  content: '> ';
  color: #7c3aed;
}

.input-section {
  background: #161b22;
  padding: 1rem;
  border: 1px solid #30363d;
  border-radius: 3px;
}

.link-input {
  width: 100%;
  padding: 0.5rem;
  background: #0d1117;
  border: 1px solid #30363d;
  color: #c9d1d9;
  font-family: inherit;
  font-size: 0.9rem;
  margin-bottom: 0.75rem;
  box-sizing: border-box;
}

.link-input:focus {
  outline: none;
  border-color: #58a6ff;
  background: #010409;
}

.link-input::placeholder {
  color: #6e7681;
}

.action-btn {
  padding: 0.5rem 1rem;
  background: #21262d;
  color: #c9d1d9;
  border: 1px solid #30363d;
  cursor: pointer;
  font-family: inherit;
  font-size: 0.9rem;
  transition: all 0.2s;
}

.action-btn:hover {
  background: #30363d;
  border-color: #58a6ff;
}

.action-btn::before {
  content: '$ ';
  color: #7c3aed;
}

.action-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.action-btn:disabled:hover {
  background: #21262d;
  border-color: #30363d;
}

.preview {
  margin-top: 1.5rem;
  padding: 1rem;
  background: #161b22;
  border: 1px solid #30363d;
  border-radius: 3px;
  font-family: inherit;
  min-height: 200px;
}

.placeholder {
  color: #6e7681;
}

.error {
  color: #f85149;
}

.json-output {
  color: #c9d1d9;
}

.json-output .vjs-tree {
  background: transparent !important;
  color: #c9d1d9 !important;
  font-family: 'Courier New', Monaco, Consolas, monospace !important;
}

.json-output .vjs-key {
  color: #58a6ff !important;
}

.json-output .vjs-value-string {
  color: #7ee787 !important;
}

.json-output .vjs-value-number {
  color: #ffa657 !important;
}


.loading-time {
  margin-top: 0.75rem;
  padding-top: 0.75rem;
  border-top: 1px solid #30363d;
  color: #6e7681;
  font-size: 0.8rem;
  display: flex;
  align-items: center;
  justify-content: flex-start;
  gap: 0.25rem;
}

.loading-time svg {
  opacity: 0.7;
}

.loading-info {
  color: #c9d1d9;
}

.request-info {
  font-family: 'Courier New', Monaco, Consolas, monospace;
}

.endpoint {
  color: #58a6ff;
  font-weight: bold;
  margin-bottom: 1rem;
}

.payload {
  margin-bottom: 1rem;
}

.payload-label {
  color: #f0883e;
  margin-bottom: 0.5rem;
  font-size: 0.9rem;
}

.payload-data {
  background: #0d1117;
  border: 1px solid #30363d;
  padding: 0.75rem;
  border-radius: 3px;
  color: #7ee787;
  white-space: pre;
  font-size: 0.85rem;
}

.payload-data .vjs-tree {
  background: transparent !important;
  color: #c9d1d9 !important;
  font-family: 'Courier New', Monaco, Consolas, monospace !important;
  font-size: 0.85rem !important;
}

.payload-data .vjs-key {
  color: #58a6ff !important;
}

.payload-data .vjs-value-string {
  color: #7ee787 !important;
}

.timer {
  color: #6e7681;
  font-size: 0.9rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.timer svg {
  opacity: 0.7;
  animation: spin 2s linear infinite;
}

@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}
</style>

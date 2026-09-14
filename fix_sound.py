import re
file_path = r'c:\xampp_2023\htdocs\bandamusicamoratalla\resources\views\welcome.blade.php'
with open(file_path, 'r', encoding='utf-8') as f:
    c = f.read()

# News modal carousel watcher for activeNewsSlide
c = c.replace(
    '''<div class="relative rounded-xl overflow-hidden bg-black mb-8 aspect-video flex items-center justify-center group/carousel">''',
    '''<div class="relative rounded-xl overflow-hidden bg-black mb-8 aspect-video flex items-center justify-center group/carousel" x-init="$watch('activeNewsSlide', () => $el.querySelectorAll('video').forEach(v => v.pause()))">'''
)

# News modal watcher for openNews
c = c.replace(
    '''<div x-data="{ openNews: false, activeNewsSlide: 0, newsSlides: ''',
    '''<div x-init="$watch('openNews', val => { if(!val) $el.querySelectorAll('video').forEach(v => v.pause()) })" x-data="{ openNews: false, activeNewsSlide: 0, newsSlides: '''
)

# History section
c = c.replace(
    '''<div class="relative rounded-xl overflow-hidden shadow-lg border border-gray-800 cursor-pointer group" @click="openLightbox = true; activeSlide = 0">''',
    '''<div x-init="$watch('openLightbox', val => { if(!val) $el.closest('[x-data]').querySelectorAll('video').forEach(v => v.pause()) })" class="relative rounded-xl overflow-hidden shadow-lg border border-gray-800 cursor-pointer group" @click="openLightbox = true; activeSlide = 0">'''
)

c = c.replace(
    '''<div class="w-full h-full flex flex-col items-center justify-center">
                                    <template x-for="(slide, index) in slides" :key="index">''',
    '''<div class="w-full h-full flex flex-col items-center justify-center" x-init="$watch('activeSlide', () => $el.querySelectorAll('video').forEach(v => v.pause()))">
                                    <template x-for="(slide, index) in slides" :key="index">'''
)

history_old = '''json_encode($bandHistoryImages->map(function($i) { return ['url' => $i->url, 'desc' => $i->description]; }))'''
history_new = '''json_encode($bandHistoryImages->map(function($i) {
                            $ext = strtolower(pathinfo($i->url, PATHINFO_EXTENSION));
                            $isVideo = in_array($ext, ['mp4', 'mov', 'webm', 'avi']);
                            return ['url' => $i->url, 'desc' => $i->description, 'type' => $isVideo ? 'video' : 'image'];
                        }))'''
c = c.replace(history_old, history_new)

history_renderer_old = '''<div x-show="activeSlide === index" x-transition.opacity.duration.300ms class="absolute inset-0 flex flex-col items-center justify-center p-4 md:p-12 z-[105]">
                                            <img :src="slide.url" class="max-h-[75vh] max-w-full object-contain rounded-lg shadow-2xl">'''
history_renderer_new = '''<div x-show="activeSlide === index" x-transition.opacity.duration.300ms class="absolute inset-0 flex flex-col items-center justify-center p-4 md:p-12 z-[105]">
                                            <template x-if="slide.type === 'video'">
                                                <video :src="slide.url" class="max-h-[75vh] max-w-full object-contain rounded-lg shadow-2xl" controls></video>
                                            </template>
                                            <template x-if="slide.type !== 'video'">
                                                <img :src="slide.url" class="max-h-[75vh] max-w-full object-contain rounded-lg shadow-2xl">
                                            </template>'''
c = c.replace(history_renderer_old, history_renderer_new)

media_old = '''json_encode($media->images->map(function($i) { return asset('storage/' . $i->file_path); }))'''
media_new = '''json_encode($media->images->map(function($i) { 
                            $ext = strtolower(pathinfo($i->file_path, PATHINFO_EXTENSION));
                            $isVideo = in_array($ext, ['mp4', 'mov', 'webm', 'avi']);
                            return ['url' => asset('storage/' . $i->file_path), 'type' => $isVideo ? 'video' : 'image'];
                        }))'''
c = c.replace(media_old, media_new)

media_inline_old = '''<img src="{{ asset('storage/' . $image->file_path) }}" class="w-full h-full object-contain select-none transition-transform duration-500 group-hover/img:scale-105" oncontextmenu="return false;" draggable="false">'''
media_inline_new = '''@php
                                              $mExt = strtolower(pathinfo($image->file_path, PATHINFO_EXTENSION));
                                              $mIsVideo = in_array($mExt, ['mp4', 'mov', 'webm', 'avi']);
                                          @endphp
                                          @if($mIsVideo)
                                              <video src="{{ asset('storage/' . $image->file_path) }}" class="w-full h-full object-contain select-none transition-transform duration-500 group-hover/img:scale-105" oncontextmenu="return false;" draggable="false" muted loop autoplay playsinline></video>
                                          @else
                                              <img src="{{ asset('storage/' . $image->file_path) }}" class="w-full h-full object-contain select-none transition-transform duration-500 group-hover/img:scale-105" oncontextmenu="return false;" draggable="false">
                                          @endif'''
c = c.replace(media_inline_old, media_inline_new)

media_modal_old = '''<template x-for="(url, index) in lightboxSlides" :key="index">
                                            <div x-show="activeLightboxSlide === index" x-transition.opacity.duration.300ms class="absolute inset-0 flex items-center justify-center p-4 md:p-12 z-[105]">
                                                <img :src="url" class="max-h-[85vh] max-w-full object-contain rounded-lg shadow-2xl">
                                            </div>
                                        </template>'''
media_modal_new = '''<template x-for="(slide, index) in lightboxSlides" :key="index">
                                            <div x-show="activeLightboxSlide === index" x-transition.opacity.duration.300ms class="absolute inset-0 flex items-center justify-center p-4 md:p-12 z-[105]">
                                                <template x-if="slide.type === 'video'">
                                                    <video :src="slide.url" class="max-h-[85vh] max-w-full object-contain rounded-lg shadow-2xl" controls></video>
                                                </template>
                                                <template x-if="slide.type !== 'video'">
                                                    <img :src="slide.url" class="max-h-[85vh] max-w-full object-contain rounded-lg shadow-2xl">
                                                </template>
                                            </div>
                                        </template>'''
c = c.replace(media_modal_old, media_modal_new)

c = c.replace(
    '''<div class="w-full h-full flex flex-col items-center justify-center">
                                        <template x-for="(slide, index) in lightboxSlides"''',
    '''<div class="w-full h-full flex flex-col items-center justify-center" x-init="$watch('activeLightboxSlide', () => $el.querySelectorAll('video').forEach(v => v.pause()))">
                                        <template x-for="(slide, index) in lightboxSlides"'''
)

c = c.replace(
    '''@keydown.escape.window="openLightbox = false"''',
    '''@keydown.escape.window="openLightbox = false" x-init="$watch('openLightbox', val => { if(!val) $el.querySelectorAll('video').forEach(v => v.pause()) })"'''
)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(c)
print('Done sound fix script')
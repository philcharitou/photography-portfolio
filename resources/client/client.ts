import * as THREE from 'three'
import { RoomEnvironment } from 'three/examples/jsm/environments/RoomEnvironment.js'
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls'
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader'
import { FBXLoader } from 'three/examples/jsm/loaders/FBXLoader'
import Stats from 'three/examples/jsm/libs/stats.module'
import { GUI } from 'dat.gui'
import { Vector3 } from 'three'
import { gsap } from 'gsap'

let camera: THREE.PerspectiveCamera
let scene: THREE.Scene
let renderer: THREE.WebGLRenderer
let animationMixer: THREE.AnimationMixer;
let fbxModel: THREE.Group;

renderer = new THREE.WebGLRenderer( { antialias: true } ) // { alpha: true }
renderer.setPixelRatio( window.devicePixelRatio )
renderer.setSize( window.innerWidth, window.innerHeight )
renderer.toneMapping = THREE.ACESFilmicToneMapping
renderer.toneMappingExposure = 1
renderer.outputEncoding = THREE.sRGBEncoding

camera = new THREE.PerspectiveCamera(50, window.innerWidth/window.innerHeight, 0.1, 2000 ) // Specify camera type like this

const environment = new RoomEnvironment();
const pmremGenerator = new THREE.PMREMGenerator( renderer )

scene = new THREE.Scene();
scene.background = new THREE.Color( 0x040f18 )
scene.environment = pmremGenerator.fromScene( environment ).texture

const width = 10;
const height = 10;
const intensity = 50;
const rectLight = new THREE.RectAreaLight( 0xffffff, intensity,  width, height );
rectLight.position.set( 5, 5, 0 );
rectLight.lookAt( 0, 0, 0 );
scene.add( rectLight )

// If exporting lights from Blender, they are very bright.
// so, you can counter this by setting renderer.useLegacyLights = false
renderer.useLegacyLights = false // WebGLRenderer.physicallyCorrectLights = true is now WebGLRenderer.useLegacyLights = false

//document.body.appendChild(renderer.domElement)

let modelReady = false
let animationDuration = 0;
let animationTime = 0;
let firstAnimationClip: THREE.AnimationClip;
let animationAction: THREE.AnimationAction;
let scrollPercentage = 0;

const animationTimeline = gsap.timeline({ paused: true });

// Detect scroll events
let isScrollingDown = false;
let scrollPosition = window.scrollY;
let scrollTimeout: string | number | NodeJS.Timeout | undefined;
const maxScrollPosition = document.body.clientHeight - window.innerHeight;
  
const fbxLoader: FBXLoader = new FBXLoader()

fbxLoader.load(
    '/cam750.fbx',
    (object) => {
        fbxModel = object;
        fbxModel.scale.set(0.001, 0.001, 0.001)
        scene.add(fbxModel)

        // Play the first animation when the model is loaded

        firstAnimationClip = fbxModel.animations[0];
        animationMixer = new THREE.AnimationMixer(fbxModel);
        modelReady = true;
        animationAction = animationMixer.clipAction(firstAnimationClip);

        animationDuration = firstAnimationClip.duration;
        animationTime = scrollPercentage * animationDuration;

        // Define forward and backward animations
        animationTimeline.to(animationAction, { value: 1, duration: 2, ease: "power1.inOut" }); // Play forward

        animationAction.setLoop(THREE.LoopOnce, 1); // Set the loop mode to play once
        animationAction.clampWhenFinished = true; // Stop animation when finished
        animationAction.play();

        camera.position.set(0,1.0,-5.1) // Set position like this
        camera.lookAt(new THREE.Vector3(0,0.3,0)) // Set look at coordinate like this
    },
    (xhr) => {
        //console.log((xhr.loaded / xhr.total) * 100 + '% loaded')
    },
    (error) => {
        console.log(error)
    }
)

// const loader = new GLTFLoader()
// loader.load(
//     '/cam750.glb',
//     function (gltf) {
//         gltf.scene.traverse(function (child) {
//             if ((child as THREE.Mesh).isMesh) {
//                 const m = child as THREE.Mesh
//                 m.receiveShadow = true
//                 m.castShadow = true
//             }
//             if ((child as THREE.Light).isLight) {
//                 const l = child as THREE.SpotLight
//                 l.castShadow = true
//                 l.shadow.bias = -0.003
//                 l.shadow.mapSize.width = 2048
//                 l.shadow.mapSize.height = 2048
//             }
//         })
//         scene.add(gltf.scene)
        
//         gltf.scene.scale.set(0.1, 0.1, 0.1)
//         gltf.scene.rotation.y = 0.18
//         gltf.scene.rotation.x = -0.38
//         camera.position.set(0,1.0,-5.1) // Set position like this
//         camera.lookAt(new THREE.Vector3(0,0.3,0)) // Set look at coordinate like this

//         let mixer = new THREE.AnimationMixer(gltf.scene)
//         const clips = gltf.scene.animations
        
//         console.log(clips)

//         // Update the mixer on each frame
//         function update () {
//             mixer.update( deltaSeconds );
//         }

//         clips.forEach( function ( clip ) {
//             mixer.clipAction( clip ).play()
//         } );
//     },
//     (xhr) => {
//         //console.log((xhr.loaded / xhr.total) * 100 + '% loaded')
//     },
//     (error) => {
//         //console.log(error)
//     }
// )


document.body.onscroll = () => {
    const scrollProgress = window.scrollY / maxScrollPosition;

    animationTimeline.progress(scrollProgress);

    console.log(animationTimeline.progress());
}

// Create an animation loop
const animate = () => {
    requestAnimationFrame(animate);

    // Render the scene
    renderer.render(scene, camera);
};

function render() {
    renderer.render(scene, camera)
}

window.addEventListener('resize', onWindowResize, false)
function onWindowResize() {
    camera.aspect = window.innerWidth / window.innerHeight
    camera.updateProjectionMatrix()
    renderer.setSize(window.innerWidth, window.innerHeight)
    render()
}

/* Liner Interpolation
 * lerp(min, max, ratio)
 * eg,
 * lerp(20, 60, .5)) = 40
 * lerp(-20, 60, .5)) = 20
 * lerp(20, 60, .75)) = 50
 * lerp(-20, -10, .1)) = -.19
 */
function lerp(x: number, y: number, a: number): number {
    return (1 - a) * x + a * y
}

window.scrollTo({ top: 0, behavior: 'smooth' })
animate()
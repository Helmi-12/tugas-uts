/**
 * Visual Commerce 3D Viewer & WebAR Configurator (Enhanced Interactive Edition)
 * Dokumen: Analisis Rancang Bangun Perangkat Lunak E-Commerce Mebel
 * Fitur: Orbit 360, Animasi Engsel Pintu Buka/Tutup, Label Dimensi 3D, Material Switcher, Android AR Intent
 */

class Aluminium3DViewer {
    constructor(containerId, options = {}) {
        this.container = document.getElementById(containerId);
        if (!this.container) return;

        this.options = Object.assign({
            width: options.width || 200,
            depth: options.depth || 60,
            height: options.height || 85,
            productType: options.productType || 'kitchen',
            currentFinish: options.finish || 'wood_grain',
            showDimensions: true,
        }, options);

        this.scene = null;
        this.camera = null;
        this.renderer = null;
        this.controls = null;
        this.furnitureGroup = null;
        this.doors = []; // Store door pivot groups for opening animation
        this.isDoorOpen = false;
        this.targetDoorAngle = 0;
        this.currentDoorAngle = 0;
        this.materials = {};

        this.init();
    }

    init() {
        const width = this.container.clientWidth || 600;
        const height = this.container.clientHeight || 450;

        // 1. Scene
        this.scene = new THREE.Scene();
        this.scene.background = new THREE.Color(0xf8fafc);

        // 2. Camera
        this.camera = new THREE.PerspectiveCamera(45, width / height, 0.1, 1000);
        this.camera.position.set(200, 140, 220);

        // 3. Renderer
        this.renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true, preserveDrawingBuffer: true });
        this.renderer.setSize(width, height);
        this.renderer.setPixelRatio(window.devicePixelRatio || 1);
        this.renderer.shadowMap.enabled = true;
        this.renderer.shadowMap.type = THREE.PCFSoftShadowMap;
        this.container.innerHTML = '';
        this.container.appendChild(this.renderer.domElement);

        // 4. Orbit Controls
        if (typeof THREE.OrbitControls !== 'undefined') {
            this.controls = new THREE.OrbitControls(this.camera, this.renderer.domElement);
            this.controls.enableDamping = true;
            this.controls.dampingFactor = 0.05;
            this.controls.maxPolarAngle = Math.PI / 2 + 0.05;
            this.controls.minDistance = 60;
            this.controls.maxDistance = 500;
        }

        // 5. Lights
        this.setupLighting();

        // 6. Environment & Floor
        this.setupEnvironment();

        // 7. Materials
        this.setupMaterials();

        // 8. Build 3D Furniture Mesh
        this.buildFurniture();

        // 9. Resize Listener
        window.addEventListener('resize', () => this.onWindowResize());

        // 10. Animation Loop
        this.animate = this.animate.bind(this);
        requestAnimationFrame(this.animate);
    }

    setupLighting() {
        const ambientLight = new THREE.AmbientLight(0xffffff, 0.8);
        this.scene.add(ambientLight);

        const dirLight1 = new THREE.DirectionalLight(0xffffff, 0.9);
        dirLight1.position.set(160, 260, 160);
        dirLight1.castShadow = true;
        dirLight1.shadow.mapSize.width = 2048;
        dirLight1.shadow.mapSize.height = 2048;
        this.scene.add(dirLight1);

        const dirLight2 = new THREE.DirectionalLight(0xbae6fd, 0.45);
        dirLight2.position.set(-160, 120, -120);
        this.scene.add(dirLight2);
    }

    setupEnvironment() {
        const grid = new THREE.GridHelper(350, 35, 0xcbd5e1, 0xe2e8f0);
        grid.position.y = -0.1;
        this.scene.add(grid);

        const floorGeo = new THREE.PlaneGeometry(500, 500);
        const floorMat = new THREE.ShadowMaterial({ opacity: 0.16 });
        const floor = new THREE.Mesh(floorGeo, floorMat);
        floor.rotation.x = -Math.PI / 2;
        floor.receiveShadow = true;
        this.scene.add(floor);
    }

    createWoodTexture() {
        const canvas = document.createElement('canvas');
        canvas.width = 512;
        canvas.height = 512;
        const ctx = canvas.getContext('2d');

        ctx.fillStyle = '#8b5a2b';
        ctx.fillRect(0, 0, 512, 512);

        ctx.strokeStyle = '#6b4423';
        ctx.lineWidth = 1.5;
        for (let i = 0; i < 512; i += 4) {
            ctx.beginPath();
            const jitter = Math.sin(i * 0.05) * 6;
            ctx.moveTo(0, i + jitter);
            ctx.bezierCurveTo(150, i - jitter, 350, i + jitter, 512, i - jitter);
            ctx.stroke();
        }

        const texture = new THREE.CanvasTexture(canvas);
        texture.wrapS = THREE.RepeatWrapping;
        texture.wrapT = THREE.RepeatWrapping;
        texture.repeat.set(2, 2);
        return texture;
    }

    setupMaterials() {
        const woodTexture = this.createWoodTexture();

        this.materials = {
            silver_anodize: new THREE.MeshStandardMaterial({
                color: 0xd1d5db,
                metalness: 0.88,
                roughness: 0.25,
            }),
            powder_black: new THREE.MeshStandardMaterial({
                color: 0x1f2937,
                metalness: 0.35,
                roughness: 0.6,
            }),
            powder_white: new THREE.MeshStandardMaterial({
                color: 0xf8fafc,
                metalness: 0.2,
                roughness: 0.35,
            }),
            powder_grey: new THREE.MeshStandardMaterial({
                color: 0x475569,
                metalness: 0.45,
                roughness: 0.5,
            }),
            wood_grain: new THREE.MeshStandardMaterial({
                map: woodTexture,
                color: 0xa16207,
                metalness: 0.15,
                roughness: 0.45,
            }),
            inner_shelf: new THREE.MeshStandardMaterial({
                color: 0x94a3b8,
                metalness: 0.7,
                roughness: 0.3,
            }),
            glass: new THREE.MeshPhysicalMaterial({
                color: 0xdbeafe,
                transmission: 0.85,
                opacity: 0.7,
                transparent: true,
                roughness: 0.1,
                ior: 1.5,
            }),
            chrome_handle: new THREE.MeshStandardMaterial({
                color: 0xffffff,
                metalness: 0.98,
                roughness: 0.05,
            }),
            dim_line: new THREE.LineBasicMaterial({
                color: 0x0284c7,
                linewidth: 2,
            })
        };
    }

    getCurrentFrameMaterial() {
        const finish = this.options.currentFinish;
        if (finish.includes('wood') || finish.includes('Kayu')) return this.materials.wood_grain;
        if (finish.includes('black') || finish.includes('Hitam')) return this.materials.powder_black;
        if (finish.includes('white') || finish.includes('Putih')) return this.materials.powder_white;
        if (finish.includes('grey') || finish.includes('Grey')) return this.materials.powder_grey;
        if (finish.includes('silver') || finish.includes('Silver')) return this.materials.silver_anodize;
        return this.materials.wood_grain;
    }

    buildFurniture() {
        if (this.furnitureGroup) {
            this.scene.remove(this.furnitureGroup);
        }

        this.furnitureGroup = new THREE.Group();
        this.doors = []; // Reset doors

        const mat = this.getCurrentFrameMaterial();
        const handleMat = this.materials.chrome_handle;
        const shelfMat = this.materials.inner_shelf;
        const glassMat = this.materials.glass;

        const w = Math.min(150, Math.max(80, this.options.width * 0.7));
        const d = Math.min(75, Math.max(38, this.options.depth * 0.7));
        const h = Math.min(150, Math.max(65, this.options.height * 0.7));
        const wallThick = 2.5;

        // 1. Back panel
        const backGeo = new THREE.BoxGeometry(w, h, wallThick);
        const backMesh = new THREE.Mesh(backGeo, mat);
        backMesh.position.set(0, h / 2, -d / 2 + wallThick / 2);
        backMesh.castShadow = true;
        this.furnitureGroup.add(backMesh);

        // 2. Left side panel
        const sideGeo = new THREE.BoxGeometry(wallThick, h, d);
        const leftSide = new THREE.Mesh(sideGeo, mat);
        leftSide.position.set(-w / 2 + wallThick / 2, h / 2, 0);
        leftSide.castShadow = true;
        this.furnitureGroup.add(leftSide);

        // 3. Right side panel
        const rightSide = new THREE.Mesh(sideGeo, mat);
        rightSide.position.set(w / 2 - wallThick / 2, h / 2, 0);
        rightSide.castShadow = true;
        this.furnitureGroup.add(rightSide);

        // 4. Bottom panel
        const bottomGeo = new THREE.BoxGeometry(w, wallThick, d);
        const bottomMesh = new THREE.Mesh(bottomGeo, mat);
        bottomMesh.position.set(0, wallThick / 2, 0);
        bottomMesh.castShadow = true;
        this.furnitureGroup.add(bottomMesh);

        // 5. Countertop / Top Profile
        const topGeo = new THREE.BoxGeometry(w + 3, 3, d + 3);
        const topMesh = new THREE.Mesh(topGeo, mat);
        topMesh.position.set(0, h + 1.5, 0);
        topMesh.castShadow = true;
        this.furnitureGroup.add(topMesh);

        // 6. Internal aluminium shelf
        const shelfGeo = new THREE.BoxGeometry(w - wallThick * 2 - 1, 1.8, d - wallThick * 2 - 2);
        const shelfMesh = new THREE.Mesh(shelfGeo, shelfMat);
        shelfMesh.position.set(0, h * 0.5, 0);
        shelfMesh.castShadow = true;
        this.furnitureGroup.add(shelfMesh);

        // 7. Hinged Doors (Interactive Opening)
        const doorCount = (w > 110) ? 3 : 2;
        const doorWidth = (w - (doorCount + 1) * 1.5) / doorCount;

        for (let i = 0; i < doorCount; i++) {
            const hingeX = -w / 2 + 1.5 + i * (doorWidth + 1.5);
            const doorPivot = new THREE.Group();
            doorPivot.position.set(hingeX, h / 2, d / 2);

            // Door Mesh (offset by half doorWidth so hinge rotates at side)
            const doorGeo = new THREE.BoxGeometry(doorWidth - 1, h - 5, 2);
            const isGlass = (i === 1 && this.options.productType === 'wardrobe');
            const doorMesh = new THREE.Mesh(doorGeo, isGlass ? glassMat : mat);
            doorMesh.position.set(doorWidth / 2, 0, 0);
            doorMesh.castShadow = true;
            doorPivot.add(doorMesh);

            // Handle
            const handleGeo = new THREE.CylinderGeometry(0.7, 0.7, 12, 16);
            const handleMesh = new THREE.Mesh(handleGeo, handleMat);
            handleMesh.position.set(doorWidth - 4, 0, 2.5);
            doorPivot.add(handleMesh);

            this.furnitureGroup.add(doorPivot);
            this.doors.push(doorPivot);
        }

        // 8. Aluminium Legs
        const legGeo = new THREE.CylinderGeometry(1.8, 1.4, 6, 16);
        const legPositions = [
            [-w / 2 + 4, 3, -d / 2 + 4],
            [w / 2 - 4, 3, -d / 2 + 4],
            [-w / 2 + 4, 3, d / 2 - 4],
            [w / 2 - 4, 3, d / 2 - 4],
        ];
        legPositions.forEach(pos => {
            const leg = new THREE.Mesh(legGeo, handleMat);
            leg.position.set(...pos);
            leg.castShadow = true;
            this.furnitureGroup.add(leg);
        });

        // 9. Dimension Line Guides
        if (this.options.showDimensions) {
            this.buildDimensionGuides(w, d, h);
        }

        this.scene.add(this.furnitureGroup);

        if (this.controls) {
            this.controls.target.set(0, h / 2, 0);
            this.controls.update();
        }
    }

    buildDimensionGuides(w, d, h) {
        // Dimension lines bounding the product
        const lineMat = this.materials.dim_line;

        // Front Width line
        const wPoints = [
            new THREE.Vector3(-w / 2, -2, d / 2 + 8),
            new THREE.Vector3(w / 2, -2, d / 2 + 8)
        ];
        const wGeo = new THREE.BufferGeometry().setFromPoints(wPoints);
        const wLine = new THREE.Line(wGeo, lineMat);
        this.furnitureGroup.add(wLine);

        // Height line
        const hPoints = [
            new THREE.Vector3(-w / 2 - 8, 0, d / 2),
            new THREE.Vector3(-w / 2 - 8, h, d / 2)
        ];
        const hGeo = new THREE.BufferGeometry().setFromPoints(hPoints);
        const hLine = new THREE.Line(hGeo, lineMat);
        this.furnitureGroup.add(hLine);
    }

    toggleDoors() {
        this.isDoorOpen = !this.isDoorOpen;
        this.targetDoorAngle = this.isDoorOpen ? -Math.PI / 2.3 : 0;
        return this.isDoorOpen;
    }

    setFinish(finishKey) {
        this.options.currentFinish = finishKey;
        this.buildFurniture();
    }

    onWindowResize() {
        if (!this.container) return;
        const width = this.container.clientWidth;
        const height = this.container.clientHeight || 450;
        this.camera.aspect = width / height;
        this.camera.updateProjectionMatrix();
        this.renderer.setSize(width, height);
    }

    animate() {
        requestAnimationFrame(this.animate);

        // Smooth door hinge rotation
        if (Math.abs(this.currentDoorAngle - this.targetDoorAngle) > 0.005) {
            this.currentDoorAngle += (this.targetDoorAngle - this.currentDoorAngle) * 0.1;
            this.doors.forEach((door, idx) => {
                door.rotation.y = this.currentDoorAngle;
            });
        }

        if (this.controls) {
            this.controls.update();
        }

        // Idle slow rotation only if closed & idle
        if (!this.isDoorOpen && this.furnitureGroup && (!this.controls || !this.controls.state || this.controls.state === -1)) {
            this.furnitureGroup.rotation.y += 0.002;
        }

        this.renderer.render(this.scene, this.camera);
    }

    takeScreenshot() {
        const dataUrl = this.renderer.domElement.toDataURL('image/png');
        const link = document.createElement('a');
        link.download = `MebelAlum-3D-${Date.now()}.png`;
        link.href = dataUrl;
        link.click();
    }
}

window.Aluminium3DViewer = Aluminium3DViewer;

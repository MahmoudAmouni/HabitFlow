const chartData = [
  { name: "Steps", values: [1200, 8500, 10200, 9800, 11000, 13000, 7500] },
  { name: "Sleep Hours", values: [7.2, 6.5, 8.0, 7.8, 6.9, 7.5, 8.2] },
  { name: "Water Intake", values: [2, 3, 4, 2, 3, 5, 4] },
  { name: "Caffeine", values: [1, 2, 1, 3, 0, 2, 1] },
];

function initCharts() {
  chartData.forEach((data, index) => {
    const containerId = `chart${index + 1}`;
    create3DChart(containerId, data.values, data.name);
  });
}

function create3DChart(containerId, data, title) {
  const container = document.getElementById(containerId);
  const width = container.clientWidth;
  const height = container.clientHeight;

  const scene = new THREE.Scene();
  scene.background = new THREE.Color(0x1a1729); // Match --card-bg

  const camera = new THREE.PerspectiveCamera(75, width / height, 0.1, 1000);
  camera.position.set(0, 0, 15);

  const renderer = new THREE.WebGLRenderer({ antialias: true });
  renderer.setSize(width, height);
  renderer.domElement.style.borderRadius = "0.5rem";
  container.appendChild(renderer.domElement);

  const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
  scene.add(ambientLight);

  const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
  directionalLight.position.set(10, 10, 10);
  scene.add(directionalLight);

  const barGeometry = new THREE.BoxGeometry(1, 1, 1);
  const barMaterial = new THREE.MeshStandardMaterial({
    color: 0x61a0ff, // Match --accent-blue
    emissive: 0x3b50e0,
    emissiveIntensity: 0.5,
  });

  const maxVal = Math.max(...data);
  const spacing = 1.5;
  const barWidth = 0.8;

  data.forEach((value, i) => {
    const barHeight = (value / maxVal) * 5; // Scale to 5 units max
    const bar = new THREE.Mesh(barGeometry, barMaterial);
    bar.scale.set(barWidth, barHeight, barWidth);
    bar.position.set(
      i * spacing - ((data.length - 1) * spacing) / 2,
      barHeight / 2,
      0
    );
    scene.add(bar);
  });

  function animate() {
    requestAnimationFrame(animate);
    renderer.render(scene, camera);
  }
  animate();

  window.addEventListener("resize", () => {
    const newWidth = container.clientWidth;
    const newHeight = container.clientHeight;
    camera.aspect = newWidth / newHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(newWidth, newHeight);
  });
}

window.addEventListener("DOMContentLoaded", initCharts);

"use client";

import React, { useEffect, useRef } from "react";

export function AnomalousMatter() {
  const canvasRef = useRef<HTMLCanvasElement>(null);

  useEffect(() => {
    const canvas = canvasRef.current;
    if (!canvas) return;
    const ctx = canvas.getContext("2d", { alpha: true });
    if (!ctx) return;

    let width = canvas.width = window.innerWidth;
    let height = canvas.height = window.innerHeight;

    const handleResize = () => {
      width = canvas.width = window.innerWidth;
      height = canvas.height = window.innerHeight;
    };
    window.addEventListener("resize", handleResize);

    // Adjust radius based on screen size
    const R = Math.min(width, height) * 0.35; 
    const rings = 40;
    const segments = 60;
    
    // Pre-calculate base vertices for a perfect sphere
    const vertices: {x: number, y: number, z: number}[] = [];
    for (let r = 0; r <= rings; r++) {
      const phi = (r * Math.PI) / rings;
      for (let s = 0; s < segments; s++) {
        const theta = (s * 2 * Math.PI) / segments;
        vertices.push({
          x: Math.sin(phi) * Math.cos(theta),
          y: Math.cos(phi),
          z: Math.sin(phi) * Math.sin(theta)
        });
      }
    }

    let time = 0;
    let animationFrameId: number;

    const draw = () => {
      time += 0.003; // Slow, mesmerizing rotation and displacement speed
      ctx.clearRect(0, 0, width, height);
      
      const cx = width / 2;
      const cy = height / 2;
      
      // Global sphere rotation
      const rotX = time * 0.4;
      const rotY = time * 0.6;
      
      const cosX = Math.cos(rotX);
      const sinX = Math.sin(rotX);
      const cosY = Math.cos(rotY);
      const sinY = Math.sin(rotY);

      const focalLength = 1000;

      // Project and Displace vertices
      const projected = vertices.map(v => {
        // Complex trigonometric interference simulating 3D noise (Anomalous Matter)
        const noise = Math.sin(v.x * 4 + time) * Math.cos(v.y * 4 + time) * Math.sin(v.z * 4 + time);
        const noise2 = Math.sin(v.x * 8 - time * 1.5) * Math.cos(v.z * 8 + time * 1.2);
        
        // Add anomalous spikes/valleys to the radius
        const radius = R + (noise * 60) + (noise2 * 25);
        
        let x = v.x * radius;
        let y = v.y * radius;
        let z = v.z * radius;
        
        // Apply X Rotation
        let y1 = y * cosX - z * sinX;
        let z1 = y * sinX + z * cosX;
        y = y1;
        z = z1;
        
        // Apply Y Rotation
        let x1 = x * cosY - z * sinY;
        let z2 = x * sinY + z * cosY;
        x = x1;
        z = z2;
        
        // 3D Perspective Projection
        const zOff = z + focalLength;
        const scale = focalLength / zOff;
        
        return {
          x: cx + x * scale,
          y: cy + y * scale,
          z: z, // Retained for depth fading/culling
        };
      });

      // Render the dense wireframe mesh
      ctx.lineWidth = 0.5;
      
      // Draw Horizontal Rings
      for (let r = 0; r <= rings; r++) {
        for (let s = 0; s < segments; s++) {
          const idx = r * segments + s;
          const nextIdx = r * segments + ((s + 1) % segments);
          
          const p1 = projected[idx];
          const p2 = projected[nextIdx];
          
          // Fade lines that are further away (depth effect)
          const avgZ = (p1.z + p2.z) / 2;
          const alpha = Math.max(0.05, Math.min(0.8, (avgZ + R) / (R * 2)));
          ctx.strokeStyle = `rgba(255, 255, 255, ${alpha})`;
          
          ctx.beginPath();
          ctx.moveTo(p1.x, p1.y);
          ctx.lineTo(p2.x, p2.y);
          ctx.stroke();
        }
      }
      
      // Draw Vertical Segments
      for (let r = 0; r < rings; r++) {
        for (let s = 0; s < segments; s++) {
          const idx = r * segments + s;
          const nextIdx = (r + 1) * segments + s;
          
          const p1 = projected[idx];
          const p2 = projected[nextIdx];
          
          const avgZ = (p1.z + p2.z) / 2;
          const alpha = Math.max(0.05, Math.min(0.8, (avgZ + R) / (R * 2)));
          ctx.strokeStyle = `rgba(255, 255, 255, ${alpha})`;
          
          ctx.beginPath();
          ctx.moveTo(p1.x, p1.y);
          ctx.lineTo(p2.x, p2.y);
          ctx.stroke();
        }
      }

      animationFrameId = requestAnimationFrame(draw);
    };

    draw();

    return () => {
      window.removeEventListener("resize", handleResize);
      cancelAnimationFrame(animationFrameId);
    };
  }, []);

  return (
    <canvas 
      ref={canvasRef} 
      className="absolute inset-0 z-[1] pointer-events-none mix-blend-screen opacity-70" 
    />
  );
}

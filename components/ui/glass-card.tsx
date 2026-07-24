import { ReactNode } from "react";
import { cn } from "@/lib/utils";

interface GlassCardProps {
  children: ReactNode;
  className?: string;
}

export function GlassCard({ children, className }: GlassCardProps) {
  return (
    <div
      className={cn(
        "bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-xl transition-all hover:bg-white/10 hover:border-white/20",
        className
      )}
    >
      {children}
    </div>
  );
}

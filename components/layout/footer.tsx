import { MountainIcon } from "lucide-react";

export function Footer() {
  return (
    <footer className="w-full bg-black/40 backdrop-blur-md border-t border-white/10 text-white py-12 px-6 md:px-12 mt-20 relative z-10">
      <div className="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
        <div className="flex items-center gap-2">
          <MountainIcon className="h-6 w-6 text-primary-foreground/50" />
          <span className="font-semibold text-primary-foreground/50">Conative Time &copy; {new Date().getFullYear()}</span>
        </div>
        <div className="flex gap-6 text-sm text-primary-foreground/50">
          <a href="#" className="hover:text-primary-foreground transition-colors">Privacy Policy</a>
          <a href="#" className="hover:text-primary-foreground transition-colors">Terms of Service</a>
        </div>
      </div>
    </footer>
  );
}

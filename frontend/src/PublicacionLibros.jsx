export default function PublicacionLibros() {
  const menuItems = [
    { label: "Iniciar sesión", icon: "👤" },
    { label: "Libros", icon: "📚" },
    { label: "Escribir libros", icon: "✍️" },
    { label: "Categoría", icon: "🔔" },
    { label: "Buscar", icon: "🔍" },
  ];

  return (
    <main className="relative min-h-screen overflow-hidden bg-[#F9FAFB] px-6 py-10 text-slate-900 sm:px-10 lg:px-16">
      <section className="mx-auto grid min-h-[calc(100vh-5rem)] max-w-6xl grid-cols-1 items-center gap-10 lg:grid-cols-[280px_1fr_72px]">
        <div className="order-2 z-10 mx-auto flex w-full max-w-xs flex-col gap-4 lg:order-1 lg:mx-0">
          {menuItems.map((item) => (
            <button
              key={item.label}
              type="button"
              className="flex h-14 w-full items-center gap-4 rounded-full border border-gray-200 bg-white px-5 text-left text-sm font-semibold text-slate-700 shadow-sm transition hover:border-blue-500 hover:bg-blue-500 hover:text-white hover:shadow-md focus:outline-none focus:ring-4 focus:ring-blue-100"
            >
              <span className="text-xl leading-none" aria-hidden="true">
                {item.icon}
              </span>
              <span>{item.label}</span>
            </button>
          ))}
        </div>

        <div className="order-1 flex min-h-[340px] items-center justify-center lg:order-2 lg:min-h-[560px]">
          <div className="relative h-[260px] w-[195px] -rotate-[15deg] rounded-2xl border border-gray-200 bg-white shadow-md sm:h-[340px] sm:w-[255px] lg:h-[390px] lg:w-[292px]">
            <div className="absolute left-8 top-0 h-full w-px bg-blue-100" />
            <div className="absolute inset-x-6 top-9 space-y-5 sm:space-y-6">
              {Array.from({ length: 10 }).map((_, index) => (
                <span
                  key={index}
                  className="block h-px w-full rounded-full bg-slate-200"
                />
              ))}
            </div>
            <div className="absolute -left-2 top-9 flex h-[78%] flex-col justify-between">
              {Array.from({ length: 7 }).map((_, index) => (
                <span
                  key={index}
                  className="h-3 w-3 rounded-full border border-gray-200 bg-[#F9FAFB] shadow-sm"
                />
              ))}
            </div>
          </div>
        </div>

        <div className="pointer-events-none fixed right-0 top-1/2 z-20 hidden -translate-y-1/2 lg:block">
          <div className="flex h-56 w-14 items-center justify-center rounded-l-2xl bg-blue-500 shadow-lg">
            <p className="rotate-90 whitespace-nowrap text-sm font-bold text-white">
              Publicación de libros
            </p>
          </div>
        </div>

        <div className="order-3 flex justify-center lg:hidden">
          <div className="rounded-full bg-blue-500 px-6 py-3 text-sm font-bold text-white shadow-md">
            Publicación de libros
          </div>
        </div>
      </section>
    </main>
  );
}

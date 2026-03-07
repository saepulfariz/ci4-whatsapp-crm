<?php
// app/Libraries/MenuCells.php
namespace App\Libraries;

class MenuCells
{
    public function renderMenu(array $params)
    {
        $request = \Config\Services::request();
        $segments = $request->getUri();

        $menus = $params['menus'];
        $parent_id = '';
        $html = '';

        foreach ($menus as $menu) {
            $hasChildren = isset($menu['children']) && !empty($menu['children']);
            // $currentRoute = service('router')->URI()->getPath(); // Mendapatkan URI saat ini
            // dd(service('router'));

            // $currentRoute = $segments->getPath(); // /dashboard
            $currentRoute = $segments->getRoutePath(); // superadmin/users

            $activeClass = '';
            $showClass = '';
            $collapsedClass = 'false';
            // $currentRoute == $menu['route']
            if ($this->areUrlsSimilar($menu['route'], $currentRoute) && ($menu['route'] || $menu['route'] != '#')) {
                $activeClass = 'active'; // Menandai menu aktif jika rute cocok
                $parent_id = $menu['parent_id'];
            }


            if ($hasChildren) {
                $submenu = $this->renderMenu(['menus' => $menu['children'], 'sub' => true]);

                if ($menu['id'] == $submenu['parent_id']) {
                    $showClass = 'menu-open';
                    $collapsedClass = 'true';
                }

                $html .= '<li class="nav-item ' . $showClass . '">';
                $html .= '<a href="#" class="nav-link ' . $activeClass . '">';
                if ($menu['icon']) {
                    $html .=  '<i class="nav-icon ' . esc($menu['icon']) . '"></i> ';
                }
                $html .= '<p>
                            ' . esc($menu['title']) . '
                            <i class="fas fa-angle-left right"></i>
                          </p>';
                $html .= '</a>';

                $html .= '  <ul class="nav nav-treeview" menu-id="' . $menu['id'] . '">';
                $html .= $submenu['html']; // Rekursif
                $html .= '  </ul>';
                $html .= '</li>';
            } else {
                $html .= '<li class="nav-item ">';
                $html .= '<a class="nav-link ' . $activeClass . '" href="' . site_url($menu['route']) . '">';
                if ($menu['icon']) {
                    $html .=  '<i class="nav-icon ' . esc($menu['icon']) . '"></i>';
                }
                $html .= '<p>' . esc($menu['title']) . '</p>';
                $html .= '</a>';
                $html .= '</li>';
            }
        }

        if (isset($params['sub'])) {
            return [
                'html' => $html,
                'parent_id' => $parent_id
            ];
        } else {
            return $html;
        }
    }

    private function areUrlsSimilar($url1, $url2)
    {
        if ($url1 == '#' || $url1 == '' || $url1 == NULL) {
            return false;
        }
        // Ambil path dari kedua URL
        $path1 = parse_url($url1, PHP_URL_PATH);
        $path2 = parse_url($url2, PHP_URL_PATH);

        // Potong path menjadi array segment
        $parts1 = array_values(array_filter(explode('/', $path1)));
        $parts2 = array_values(array_filter(explode('/', $path2)));

        // Hitung depth berdasarkan jumlah segment di url1
        $depth = count($parts1);

        // Ambil base segment dari kedua URL berdasarkan depth
        $base1 = array_slice($parts1, 0, $depth);
        $base2 = array_slice($parts2, 0, $depth);

        // Bandingkan segmen awal
        return $base1 === $base2;
    }

    /**
     * Merender menu dalam struktur UL/LI yang dapat diurutkan.
     * @param array $params Mengandung 'menus'
     * @return string HTML
     */
    public function renderSortableMenu(array $params)
    {
        $menus = $params['menus'];
        $html = '<ul class="sortable-list">'; // Tambahkan class untuk inisialisasi SortableJS

        foreach ($menus as $menu) {
            $hasChildren = isset($menu['children']) && !empty($menu['children']);

            $html .= '<li data-id="' . esc($menu['id']) . '">';
            $html .= '<div class="menu-item-content">'; // Konten item menu
            $html .= '<i class="' . esc($menu['icon']) . '"></i> ';
            $html .= '<span class="menu-item-title">' . esc($menu['title']) . '</span>';
            $html .= '<span class="menu-item-id">(ID: ' . esc($menu['id']) . ')</span>'; // Untuk debugging
            $html .= '</div>';

            if ($hasChildren) {
                $html .= $this->renderSortableMenu(['menus' => $menu['children']]); // Rekursif
            } else {
                $html .= '<ul class="sortable-list"></ul>';
            }
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }
}
